<?php
/**
 * Tools.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/25 12:46
 */

namespace Deploy\Lib;


class Tools
{
    public static function getCustomerAddress()
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';

        if ($ip == '') {
            return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }

        $ips = explode(',', $ip);
        if (count($ips) === 0) {
            return '';
        }

        return $ips[0];
    }

}