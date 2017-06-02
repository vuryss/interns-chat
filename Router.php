<?php


class Router
{
    private $query;
    private $method;

    private $routes = [
        [
            'method'           => 'GET',
            'action'           => 'register',
            'controller'       => 'Register',
            'controllerAction' => 'form',
        ],
        [

            'method'           => 'POST',
            'action'           => 'register',
            'controller'       => 'Register',
            'controllerAction' => 'register',
        ],
        [
            'method'           => 'GET',
            'action'           => 'chat',
            'controller'       => 'Chat',
            'controllerAction' => 'view',
        ]
    ];

    public function __construct()
    {
        $this->query = $_GET;
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function route()
    {
        $action = isset($this->query['action']) ? $this->query['action'] : 'chat';
        $matchedRoute = [];

        foreach ($this->routes as $route) {
            if ($route['method'] === $this->method && $route['action'] === $action) {
                $matchedRoute = $route;
                break;
            }
        }

        return [$matchedRoute['controller'], $matchedRoute['controllerAction']];
    }
}
