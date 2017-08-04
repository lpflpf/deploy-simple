<?php
/**
 * Controller.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/16 18:38
 */

namespace Deploy\Controller;

use Deploy\Config\Configure;
use Deploy\Lib\M3Result;

abstract class Controller
{
    /**
     * @return string
     */
    public function getControllerName()
    {
        $name = get_class($this);
        $token = explode("\\", $name);
        $controllerFullName = array_pop($token);
        $controllerFullName = substr($controllerFullName, 0, strpos($controllerFullName, 'Controller'));
        return strtolower($controllerFullName);
    }

    /**
     * @param       $viewName
     * @param array $params
     *
     */
    public function render($viewName, $params = [])
    {

        $controller = $this->getControllerName();
        $filePath = $controller . '/' . $viewName . '.twig';
        $loader = new \Twig_Loader_Filesystem(Configure::getConfig(static::$viewPath));
        $twig = new \Twig_Environment($loader, $this->options);
        $this->output = $twig->render($filePath, $params);
    }

    public function renderJSON($errCode, $msg, $data = [])
    {
        header("Content-type: application/json");
        $m3Result = new M3Result();
        $m3Result->status = $errCode;
        $m3Result->message = $msg;
        $m3Result->data = $data;
        $this->output = $m3Result->toJson();
    }

    public function beforeAction()
    {
        return true;
    }

    public function afterAction()
    {
        header("Content-type: text/html; charset=utf-8");
        echo $this->output;
    }

    public static $viewPath = 'web.view.path';

    public $options = [
        'charset' => 'UTF-8',
    ];

    public $output = '';
}