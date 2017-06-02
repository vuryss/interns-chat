<?php

class FrontController
{
    public function start()
    {
        session_start();

        $router = new Router();
        $route = $router->route();

        $controllerName = $route[0] . 'Controller';

        $controller = new $controllerName();

        /** @var View $view */
        $view = $controller->{$route[1]}();

        echo $view->getContent();
    }
}
