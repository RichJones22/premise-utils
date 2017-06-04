<?php declare(strict_types=1);

namespace Premise\Tests;

use PHPUnit_Framework_TestCase;
use Premise\Utilities\CheckForLocalLogging;
use Tests\CreatesApplication;

class CheckForLocalLoggingTest extends PHPUnit_Framework_TestCase
{
    use CreatesApplication;

    private $app;

    private $tempEnvFile='tempEnvFile';

    private $appLocalLogTrue = "APP_LOCAL_LOG=true";
    private $appLocalLogFalse = "APP_LOCAL_LOG=false";
    private $appLocalLogEmpty = "APP_LOCAL_LOG=false";

    public function setUp()
    {
        $this->app = $this->createApplication();

        parent::setUp();
    }

    public function testCheckIfEnvFileDoesNotExists()
    {
        try {
            new CheckForLocalLogging('bob');
        } catch (\Exception $e) {
            $this->assertEquals(CheckForLocalLogging::ENV_FILE_NOT_FOUND, $e->getMessage());
        }
    }

    public function testCheckIfEnvVarAppEnvDoesNotExist()
    {
        try {
            try {
                $this->createTempEnvFile();
            } catch (\Exception $e) {
                echo $e->getMessage();
                die();
            }

            new CheckForLocalLogging($this->tempEnvFile);
        } catch (\Exception $e) {
            // fail if we get here...
            $this->assertEquals(CheckForLocalLogging::APP_ENV_NOT_FOUND, $e->getMessage());
        }
    }

    private function createTempEnvFile()
    {
        if (file_exists($this->tempEnvFile)) {
            $this->removeTempEnvFile();
        }

        if (file_exists($this->tempEnvFile)) {
            throw new \Exception("the file: {$this->tempEnvFile} exists and should not.  Please remove this file...");
        }

        file_put_contents($this->tempEnvFile, $this->appLocalLogEmpty);

        if ( ! file_exists($this->tempEnvFile)) {
            throw new \Exception("unable to create file: {$this->tempEnvFile}.");
        }
    }

    private function removeTempEnvFile()
    {
        unlink($this->tempEnvFile);
    }
}