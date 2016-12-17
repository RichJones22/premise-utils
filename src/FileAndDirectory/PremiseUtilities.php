<?php

declare(strict_types=1);

namespace Premise\Utilities;

use DirectoryIterator;
use SplFileInfo;

/**
 * Class PremiseUtilities.
 */
class PremiseUtilities
{
    /**
     * returns files as strings.
     *
     * culls for a specific file extension, ext; or not... does both...
     *
     * @param $path
     * @param null $ext
     *
     * @return array
     */
    public static function getFileForDirectory($path, $ext = null)
    {
        $matchString = '/^.+\.'.$ext.'$/i';

        $files = [];
        $dir = new DirectoryIterator($path);

        /** @var DirectoryIterator $fileInfo */
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isFile()) {
                // any file extension is okay.
                if ($ext === null) {
                    $files[] = $fileInfo->getFilename();
                } else {
                    // only looking for a specific file extension
                    if (preg_match($matchString, $fileInfo->getFilename())) {
                        $files[] = $fileInfo->getPathName();
                    }
                }
            }
        }

        return $files;
    }

    /**
     * returns files objects of type DirectoryIterator.
     *
     * culls for a specific file extension, ext; or not... does both...
     *
     * @param $path
     * @param null $ext
     *
     * @return array|DirectoryIterator
     */
    public static function getFileObjectsForDirectory($path, $ext = null): SplFileInfo
    {
        $matchString = '/^.+\.'.$ext.'$/i';

        $files = [];
        $dir = new DirectoryIterator($path);

        /** @var DirectoryIterator $fileInfo */
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isFile()) {
                // any file extension is okay.
                if ($ext === null) {
                    $files[] = new SplFileInfo($fileInfo->getFilename());
                } else {
                    // only looking for a specific file extension
                    if (preg_match($matchString, $fileInfo->getFilename())) {
                        $files[] = new SplFileInfo($fileInfo->getFilename());
                    }
                }
            }
        }

        return $files;
    }

    /**
     * @param $path
     *
     * @return array
     */
    public static function getDirectoriesForDirectory($path)
    {
        $files = [];
        $iterator = new DirectoryIterator($path);
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir() && ! $fileInfo->isDot()) {
                $files[] = $fileInfo->getFilename();
            }
        }

        return $files;
    }

    /**
     * @param $path
     *
     * @return array
     */
    public static function getPhpFilesInDirectoryRecursive($path)
    {
        $files = [];
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $fileInfo) {
            $files[] = $fileInfo->getFilename();
        }

        return $files;
    }

    /**
     * @param $path
     * @param string $ext
     *
     * @return array
     */
    public static function getFilesInDirectoryRecursiveByFileExt($path, $ext = 'PHP')
    {
        $matchString = '/^.+\.'.$ext.'$/i';

        $files = [];
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                if (preg_match($matchString, $fileInfo->getFilename())) {
                    $files[] = $fileInfo->getPathName();
                }
            }
        }

        return $files;
    }
}
