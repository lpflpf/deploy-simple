<?php
/**
 * Command.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/28 10:08
 */

namespace Deploy\Helper;


use Deploy\Config\Configure;

abstract class Command
{
    protected $errorInfo = '';

    /**
     * 通过参数构造命令
     *
     * @param $params
     *
     * @return mixed
     */
    public abstract function get($params);

    /**
     * 获取错误信息
     *
     * @return string
     */
    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getErrors()
    {
        return $this->errorInfo;
    }

    /**
     * 通过目录名，获取配置文件
     *
     * @param $dirName
     *
     * @return null
     */
    protected function getFileConfig($dirName)
    {
        $syncDirs = Configure::getConfig('simple.syncDirs');

        foreach ($syncDirs as $value) {
            if (!isset($value)) {
                continue;
            }
            $path = $value['path'];

            $path = str_replace("\\", "/", $path);
            $path = trim($path, "/");
            $syncDirs[$path] = $value;
        }
        header("Content-type:text/html; charset=utf-8");
        $dirName = str_replace("\\", "/", $dirName);
        $dirName = trim($dirName, "/");

        while (strlen($dirName)) {
            if (isset($syncDirs[$dirName])){
                return $syncDirs[$dirName];
            }

            $token = explode("/", $dirName);
            $dirName = implode("/", array_slice($token, 0, -1));

        }
        $this->errorInfo = 'CANNOT FILE SYNC DIRECTORY IN CONFIG FILE. PLEASE CHECKED IT.';
        return false;
    }
}