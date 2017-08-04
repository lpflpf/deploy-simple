<?php
/**
 * index.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/16 10:58
 */

define("APP_PATH",  __DIR__ . '/../');
require(APP_PATH . "vendor/autoload.php");

(new Deploy\WebApplication())->run();
