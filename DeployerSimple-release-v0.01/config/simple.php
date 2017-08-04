<?php
/**
 * simple.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/25 19:41
 */

return [
    // 简单模式的所需配置
    'syncDirs' => [
        [
            'name' => '单品页sys.php',
            'path' => 'product.dangdang.com/',
//            'dstPath' => '/var/www/product.dangdang.com/config/',
            'dstPath' => '/tmp',
            'type' => 'php',
        ],
        [
            'name' => '单品页api.php',
            'path' => 'product.dangdang.com/',
//            'dstPath' => '/var/www/product.dangdang.com/config/',
            'dstPath' => '/tmp',
            'type' => 'php',
        ],
        [
            'name' => '单品API配置',
            'path' => 'productapi/',
//            'dstPath' => '/var/www/hosts/productapi/v2/',
            'dstPath' => '/tmp',
            'type' => 'php',
        ],
        [
            'name' => '拼团架构作业',
            'path' => 'ProductPinTuanSyncJob/',
            'dstPath' => '/tmp/ProductPinTuanSyncJob/',
            'type' => 'java',
            'processName' => 'ProductPinTuanSyncJob',
            'jobPath' => '/tmp/ProductPinTuanSyncJob/',
        ]
    ],
    'command' => [
        1 => 'SyncHelper',
        2 => 'ExecHelper',
    ]
];