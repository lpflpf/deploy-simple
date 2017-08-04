<?php
/**
 * DeployController.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/25 19:37
 */

namespace Deploy\Controller;

use Deploy\Helper\CommandFactory;
use Deploy\Model\Cluster;
use Deploy\Config\Configure;
use Deploy\Lib\FileSystem;
use Deploy\Model\IpList;
use Deploy\Model\Record;

class DeployController extends Controller
{
    public function index()
    {
        $files = Configure::getConfig('simple.configFiles');
        $clusters = Cluster::getClusters();
        session_id('simple');
        $this->render('index', [
            'files' => $files,
            'clusters' => $clusters,
            'dir' => json_encode(FileSystem::getDirFiles())
        ]);
    }

    /**
     * check php syntax.
     * 1. put content to /tmp/check_file_xxx
     * 2. exec php -l
     * 3. return result.
     */
    public function checkSyntax()
    {
        $fileContent = $_POST['content'];

        $path = '/tmp/check_file_' . rand(10000, 10000000);
        file_put_contents($path, $fileContent);
        $cmd = "php -l {$path}";
        exec($cmd, $outputs);
        if (false === strpos(implode("", $outputs), "No syntax errors detected")) {
            $this->renderJSON(-3, 'syntax errors! check file');
            return;
        }
        $this->renderJSON(0, '');

        unlink($path);
    }

    /**
     * remote exec command.
     */
    public function exec()
    {
        $commandType = $_GET['type'];
        $batchId = $_GET['batchId'];
        $ip = $_GET['ip'];

        $commandObj = CommandFactory::getCommand($commandType);
        $command = $commandObj->get($_GET);
        // get command failed, return error message.
        if (!$command) {
            $this->renderJSON(-1, $commandObj->getErrors());
            return;
        }

        $command = str_replace('{ip}', $ip, $command);

        $begin = time();
        exec($command, $outputs, $status);

        $log = mb_convert_encoding(implode("\n", $outputs), 'utf-8');

        // record log into db.
        Record::set($batchId, $ip, $command, $log, $status, time() - $begin);

        $this->renderJSON($status, '', $log);
    }

    public function getProgress()
    {
        $logFile = $_GET['progressId'];
        $processId = $_GET['processId'];

        $result = [];

        $logFile = "/tmp/" . $logFile;

        $command = "cat $logFile";
        exec($command, $outputs);
        $result['log'] = mb_convert_encoding(implode("\n", $outputs), 'utf-8');

        if (file_exists('/proc/' . $processId)) {
            $result['process'] = false;
        } else {
            $result['process'] = true;
            unlink($logFile);
        }

        $this->renderJSON(0, '', $result);
    }

    public function restart()
    {
        $clusterId = $_POST['clusterId'] ?? '';
        $doFile = $_POST['file'] ?? '';

        if (empty($doFile) || empty($clusterId)) {
            $this->renderJSON(-2, 'post params error!');
            return;
        }

        $doConfig = $this->getConfig($doFile);
        if (!$doConfig) {
            return;
        }

        $key = rand(0, 10000);
        $log_path = "/tmp/_restart_" . $key;
        $ipList = implode("\n", IpList::getIpList($clusterId));

        $cmd = '';
        if ($doConfig['type'] === 'php') {
            $cmd = <<<END
echo \$\$; > {$log_path}; ips="{$ipList}";  for ip in \$ips;     do ssh root@\$ip "/sbin/service php-fpm restart" >> {$log_path}; done &
END;
        } else {
            if ($doConfig['type'] === 'java') {
                $processName = $doConfig['processName'];
                $jobPath = $doConfig['jobPath'];
                $cmd = <<<END
echo \$\$; > {$log_path}; ips="{$ipList}"; for ip in \$ips; do     ssh root@\$ip 'kill -9 {$processName}'; ssh root@\$ip 'cd {$jobPath}; java -jar {$processName}.jar &;'; done &
END;
            }
        }
        exec($cmd, $outputs);
        $this->renderJSON(0, '', [
            'progressId' => "_restart_" . $key,
            'processId' => $outputs[0],
        ]);
    }

    private function getConfig($doFile)
    {

        // 获取配置
        $doConfig = [];
        $files = Configure::getConfig('simple.configFiles');
        foreach ($files as $config) {
            if ($config['path'] === $doFile) {
                $doConfig = $config;
                break;
            }
        }
        if (empty($doConfig)) {
            $this->renderJSON(-1, 'cannot find file', '');
            return false;
        }
        return $doConfig;
    }
}