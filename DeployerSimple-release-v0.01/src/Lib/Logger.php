<?php

namespace Deploy\Lib;

use Deploy\Config\Configure;

/**
 * Logger.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/16 9:42
 */
class Logger {

    public static function info($message) {
        if (Configure::getConfig('cmd.log.level') != 'info'){
            return;}
        $time = time();
        $fp = file_put_contents(Configure::getConfig(static::$logDir) . date('Ymd', $time) . '.info', date('Y-m-d H:i:s', $time) . "\t". $message . PHP_EOL, FILE_APPEND);
    }
    public static function error($message) {
        $time = time();
        $fp = file_put_contents(Configure::getConfig(static::$logDir) . date('Ymd', $time) . '.error', date('Y-m-d H:i:s', $time) . "\t" . $message . PHP_EOL, FILE_APPEND);
    }

    public static function print_stack_trace($msg, callable $log_handler = null, $endline = "\n", $exit=false){
        $trace = debug_backtrace();
        $num = 0;
        $ans = 'message:'.$msg.$endline.'stact trace back :'.$endline;
        foreach($trace as $line){
            $ans .= '#'.$num.' '.$line['file'].'['.$line['line'].'] ';
            if($line['type'] == '->' || $line['type'] == '::'){
                $ans .= $line['class'].$line['type'].$line['function'].'()';
            }else{
                $ans .= $line['function'].'()';
            }
            if(!empty($line['args'])){
                $ans .= $endline.'parameters:'.$endline.print_r($line['args'], true);
            }
            if(!empty($line['object'])){
                $ans .= $endline.'object:'.$endline.print_r($line['object'], true);
            }
            $ans .= $endline;
            $num++;
        }
        if($log_handler != null && function_exists($log_handler)){
            $log_handler($ans);
        }else{
            return $ans;
        }
        if($exit){
            exit(1);
        }
    }

    public static $logDir = 'cmd.log.dir';

}