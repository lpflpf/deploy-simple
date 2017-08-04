<?php

namespace Deploy\Helper;

use Deploy\Lib\FileSystem;

/**
 * SyncHelper.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/28 10:01
 */
class SyncHelper extends Command
{

    /**
     * CONSTRUCT COMMAND BY PARAMS.
     *
     * @param $params
     *
     * @return mixed
     */
    public function get($params)
    {
        $fileName = $params['fileName'];
        $dirName = $params['dirName'];

        $config = $this->getFileConfig($dirName);

        // CANNOT FILE CONFIG, RETURN ERROR JSON.
        if (!$config) {
            return false;
        }

        $dstPath = $config['dstPath'] . DIRECTORY_SEPARATOR . $dirName;
        $srcPath = FileSystem::getSimpleDir() . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $fileName;
        return <<<SHELL
        ssh root@{ip} "mkdir -p {$dstPath}";   
        rsync -avP {$srcPath} {ip}:{$dstPath} 2>&1
SHELL;

    }
}