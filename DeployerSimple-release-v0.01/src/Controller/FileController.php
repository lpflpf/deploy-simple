<?php
/**
 * FileController.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/13 13:41
 */

namespace Deploy\Controller;

use Deploy\Lib\FileSystem;

class FileController extends Controller
{
    public function get()
    {
        $filename = $_GET['name'];
        $directoryName = $_GET['dir'];
        $fileContent = FileSystem::getFileContent($directoryName . DIRECTORY_SEPARATOR . $filename);
        $encoding = mb_detect_encoding($fileContent, ['gbk', 'utf-8']);
        if ($encoding !== 'UTF-8') {
            $fileContent = mb_convert_encoding($fileContent, 'utf-8', $encoding);
        }
        $this->output = $fileContent;
    }

    public function write()
    {
        $fileName = $_POST['name'];
        $directoryName = $_POST['dir'];
        $content = $_POST['content'];

        $filePath = FileSystem::getSimpleDir() . $directoryName . DIRECTORY_SEPARATOR . $fileName;

        $hashCode = filemtime($filePath);
        $dstName = $filePath . "_" . $hashCode;

        // set file encoding to utf-8
        $encoding = mb_detect_encoding(file_get_contents($filePath), ['gbk', 'utf-8']);
        if ($encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, $encoding);
        }

        $groupName = filegroup($filePath);
        $userName = fileowner($filePath);
        $mode = fileperms($filePath);
        if (!file_exists($dstName)) {
            rename($filePath, $dstName);
        }
        file_put_contents($filePath, $content);
        chmod($filePath, $mode);
        chown($filePath, $userName);
        chgrp($filePath, $groupName);
        $outputs = [];

        exec("diff  " . $dstName . "  " . $filePath, $outputs);
        $outputs = implode("\n", $outputs);

        if (empty($outputs)) {
            $outputs = 'FILE NOT CHANGED.';
        }
        $this->output = $outputs;
    }
}