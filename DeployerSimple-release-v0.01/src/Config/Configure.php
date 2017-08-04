<?php
/**
 * Configure.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/16 11:06
 */

namespace Deploy\Config;


class Configure
{
    private static $config = [];

    private static function loadConfig(string $path)
    {
        $filePath = APP_PATH . 'config/' . $path . '.php';

        if (file_exists($filePath)) {
            static::$config[$path] = require($filePath);
        } else {
            static::$config[$path] = [];
        }
    }

    static function getConfig(string $path, $default = '')
    {
        $keys = explode(".", $path);

        if (!isset(static::$config[$keys[0]])) {
            self::loadConfig($keys[0]);
        }

        $map = static::$config[$keys[0]];
        for ($i = 1; $i < count($keys); $i++) {
            if (isset($map[$keys[$i]])) {
                $map = $map[$keys[$i]];
                continue;
            }
            return $default;
        }

        return $map;
    }
}