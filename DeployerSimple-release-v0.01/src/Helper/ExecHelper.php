<?php

namespace Deploy\Helper;

/**
 * ExecHelper.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/28 10:06
 */
class ExecHelper extends Command
{

    /**
     * get commands by some params.
     *
     * @param $params
     *
     * @return mixed
     */
    public function get($params)
    {
        $type = strtolower($params['commandType']);


        $dirName = $params['dirName'];
        $config = $this->getFileConfig($dirName);
        $type = $config['type'];
        if ($type === 'php') {
            return 'ssh root@{ip} "service php-fpm restart 2>&1"';
        }
        if ($type === 'hhvm'){
            return 'ssh root@{ip} "service hhvm restart 2>&1"';
        }
        if ($type === 'java') {
            $processName = $config['processName'] ?? '';
            $jobPath = $config['jobPath'] ?? '';
            if (empty($processName) || empty($jobPath)) {
                $this->errorInfo = 'NOT SET PROCESS NAME OR JOB PATH, PLEASE CHECK CONFIG FILE.';
                return false;
            }

            return <<<END
ssh root@{ip} 'pid=`ps aux | grep ProductPinTuanSyncJob | grep -v grep | awk '"' {print \\$2}'"'`;
if [ -n "\$pid" ];
then
kill -9 \$pid 2>&1;
fi
cd /tmp/ProductPinTuanSyncJob/;
nohup java -jar ProductPinTuanSyncJob.jar 1>/dev/null 2>&1 &
'
END;
        }

        $this->errorInfo = 'CANNOT REGISTER COMMAND FOR THIS TYPE (' . $type . ').';

        return false;
    }
}