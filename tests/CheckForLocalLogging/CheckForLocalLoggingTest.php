<?php

declare(strict_types=1);

namespace Premise\Tests;

use Exception;
use PHPUnit_Framework_TestCase;
use Premise\Utilities\CheckForLocalLogging;
use Tests\CreatesApplication;

class CheckForLocalLoggingTest extends PHPUnit_Framework_TestCase
{
    use CreatesApplication;

    /**
     * @var Laravel app
     */
    private $app;

    /**
     * @var string
     */
    private $tempEnvFile = 'tempEnvFile';

    /**
     * @var string
     */
    private $envEqualLocalAppLocalLogEqualFalse = 'APP_ENV=local'.PHP_EOL.'APP_LOCAL_LOG=false';
    /**
     * @var string
     */
    private $envEqualLocalAppLocalLogEqualTrue = 'APP_ENV=local'.PHP_EOL.'APP_LOCAL_LOG=true';
    /**
     * @var string
     */
    private $envEqualProductionAppLocalLogEqualFalse = 'APP_ENV=production'.PHP_EOL.'APP_LOCAL_LOG=false';
    /**
     * @var string
     */
    private $envEqualProductionAppLocalLogEqualTrue = 'APP_ENV=production'.PHP_EOL.'APP_LOCAL_LOG=true';

    /**
     * @var string
     */
    private $appEnvProduction = 'APP_ENV=false';

    /**
     * setup each test.
     */
    public function setUp()
    {
        $this->app = $this->createApplication();

        parent::setUp();
    }

    /**
     * tear down each test.
     */
    public function tearDown()
    {
        $this->removeTemporaryEnvironmentFile();

        parent::tearDown();
    }

    public function testCheckIfEnvFileDoesNotExists()
    {
        try {
            new CheckForLocalLogging('bob');
        } catch (Exception $e) {
            $this->assertSame(CheckForLocalLogging::ENV_FILE_NOT_FOUND, $e->getMessage());
        }
    }

    public function testCheckIfEnvVarAppEnvDoesNotExist()
    {
        try {
            $this->CreateEnvironmentFile('');

            new CheckForLocalLogging($this->tempEnvFile);
        } catch (Exception $e) {
            // fail if we get here...
            $this->assertSame(CheckForLocalLogging::APP_ENV_NOT_FOUND, $e->getMessage());
        }
    }

    public function testCheckThatEnvEqualLocalAppLocalLogEqualFalse()
    {
        $this->CreateEnvironmentFile($this->envEqualLocalAppLocalLogEqualFalse);

        $result = new CheckForLocalLogging($this->tempEnvFile);

        $this->assertSame(false, $result->isLogLocalSet());
    }

    public function testCheckThatEnvEqualLocalAppLocalLogEqualTrue()
    {
        $this->CreateEnvironmentFile($this->envEqualLocalAppLocalLogEqualTrue);

        $result = new CheckForLocalLogging($this->tempEnvFile);

        $this->assertSame(true, $result->isLogLocalSet());
    }

    public function testCheckThatEnvEqualProductionAppLocalLogEqualFalse()
    {
        $this->CreateEnvironmentFile($this->envEqualProductionAppLocalLogEqualFalse);

        $result = new CheckForLocalLogging($this->tempEnvFile);

        $this->assertSame(false, $result->isLogLocalSet());
    }

    public function testCheckThatEnvEqualProductionAppLocalLogEqualTrue()
    {
        $this->CreateEnvironmentFile($this->envEqualProductionAppLocalLogEqualTrue);

        $result = new CheckForLocalLogging($this->tempEnvFile);

        $this->assertSame(false, $result->isLogLocalSet());
    }

    public function testCheckLogLocal()
    {
        $this->CreateEnvironmentFile($this->envEqualLocalAppLocalLogEqualTrue);

        $result = CheckForLocalLogging::checkLogLocal($this->tempEnvFile);

        $this->assertSame(true, $result);
    }

    public function testIsLogLocalSetTrue()
    {
        $this->CreateEnvironmentFile($this->envEqualLocalAppLocalLogEqualTrue);

        $result = new CheckForLocalLogging($this->tempEnvFile);

        $this->assertSame(true, $result->isLogLocalSet());
    }

    public function testIsLogLocalSetFalse()
    {
        $this->CreateEnvironmentFile($this->envEqualProductionAppLocalLogEqualTrue);

        $result = new CheckForLocalLogging($this->tempEnvFile);

        $this->assertSame(false, $result->isLogLocalSet());
    }

    public function testGetEnvironment()
    {
        $this->CreateEnvironmentFile($this->envEqualProductionAppLocalLogEqualTrue);

        $result = new CheckForLocalLogging($this->tempEnvFile);

        $this->assertSame('production', $result->getEnvironment());
    }

    /**
     * @param string $contents
     */
    protected function CreateEnvironmentFile(string $contents): void
    {
        try {
            $this->createTempEnvFile($contents);
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    /**
     * @param string $contents
     *
     * @throws Exception
     */
    private function createTempEnvFile(string $contents)
    {
        $this->removeTemporaryEnvironmentFile();

        if (file_exists($this->tempEnvFile)) {
            throw new Exception("the file: {$this->tempEnvFile} exists and should not.  Please remove this file...");
        }

        file_put_contents($this->tempEnvFile, $contents);

        if ( ! file_exists($this->tempEnvFile)) {
            throw new Exception("unable to create file: {$this->tempEnvFile}.");
        }
    }

    private function removeTempEnvFile()
    {
        unlink($this->tempEnvFile);
    }

    private function removeTemporaryEnvironmentFile(): void
    {
        if (file_exists($this->tempEnvFile)) {
            $this->removeTempEnvFile();
        }
    }
}
