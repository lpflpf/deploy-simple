<?php
/**
 * Dispatcher.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/16 17:32
 */

namespace Deploy;


use Deploy\Controller\Controller;

class Dispatcher
{
    public function __construct()
    {
    }

    /**
     * @param       $route
     * @param array $params
     */
    public function dispatch($params = [])
    {
        $route = $this->getRoute();
        $route = trim($route, "/");
        list($controller, $action) = explode('/', $route);

        $controller = ucfirst($controller);
        $className = static::$defaultClassNamespace . $controller . 'Controller';
        $actionName = lcfirst($action);

        /**
         * @var Controller $controller
         */
        $controller = new $className;
        if ($controller->beforeAction()) {
            $controller->$actionName($params);

        }
        $controller->afterAction();
    }

    public function getRoute()
    {
        $route = $_GET['r'] ?? '';
        if (empty($route)) {
            $route = $_SERVER['REQUEST_URI'];
            if (!empty($route) && $route != '/') {
                $pos = strpos($route, '?');
                if ($pos) {
                    $route = substr($route, 1, $pos - 1);
                } else {
                    $route = substr($route, 1);
                }
            } else {
                $route = 'Index/index';
            }
        }

        return $route;
    }

    private static $defaultClassNamespace = '\\Deploy\\Controller\\';
}