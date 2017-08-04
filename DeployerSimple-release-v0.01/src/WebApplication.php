<?php
/**
 * WebApplication.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/16 17:44
 */

namespace Deploy;


class WebApplication
{
    public function run()
    {
        $this->beforeDispatch();
        (new Dispatcher())->dispatch();
    }

    public function beforeDispatch()
    {
        session_start();
        if (!isset($_SESSION['userid']) && ($_GET['r'] != 'index/login' && $_GET['r'] != 'index/doLogin')) {
            header("Location:/index/login");
            exit();
        }
    }
}