<?php
/**
 * router.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/25 9:34
 */

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|woff|tff|ico)$/', $_SERVER['PHP_SELF'])) {
    return false;
}

$path = str_replace('index.php', '', $_SERVER['PHP_SELF']);

$route = trim($path, "/");
$_GET['r'] = trim($route, "/");
if (empty($_GET['r'])) {
    $_GET['r'] = '/deploy/index';
}
require("../public/index.php");