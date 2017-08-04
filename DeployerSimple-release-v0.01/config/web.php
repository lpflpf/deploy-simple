<?php
/**
 * web.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/16 11:35
 */

return [
    'server' => [
        'path' => '',       // 服务器ip的保存目录
        'baseUri' => 'http://lpf.deploy.com',
        'dbFileName' => APP_PATH . 'storage/deploy.db',
        'fileListDir' => APP_PATH . 'storage/file-list',
    ],
    'view' => [
        'path' => APP_PATH . 'resource/view/',
    ],
];