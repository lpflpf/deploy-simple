<?php
/**
 * FileSystem.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/21 10:16
 */

namespace Deploy\Lib;


use Deploy\Config\Configure;

class FileSystem
{
    public static $userPath = '';

    public static function getUserPath()
    {
        if (empty(static::$userPath)) {
            static::$userPath = Configure::getConfig('web.server.fileListDir') . DIRECTORY_SEPARATOR;
        }

        if (!is_dir(static::$userPath)) {
            mkdir(static::$userPath, 0755);
        }
        return static::$userPath;
    }

    public static function getDirFiles()
    {
        $dir = static::getSimpleDir();
        return static::getDir('/', $dir);
    }

    private static function getDir($dir, $basePath = '')
    {
        $files = scandir($basePath . DIRECTORY_SEPARATOR . $dir);
        $result = [];
        foreach ($files as $file) {
            $path = $basePath . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file;
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (preg_match('/^.*_\d{10}$/', $file)) {
                continue;
            }

            $node = [
                'name' => $file,
                'path' => $dir,
            ];
            if (is_dir($path)) {
                $node['children'] = static::getDir($dir . DIRECTORY_SEPARATOR . $file, $basePath);
            }
            $result[] = $node;
        }

        return $result;
    }

    public static function getFileContent($fileName)
    {
        return file_get_contents(self::getSimpleDir() . DIRECTORY_SEPARATOR . $fileName);
    }

    public static function getSimpleDir()
    {
        return Configure::getConfig('web.server.fileListDir') . DIRECTORY_SEPARATOR;
    }
}