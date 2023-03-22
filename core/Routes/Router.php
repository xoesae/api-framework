<?php

namespace Core\Routes;

use Core\Requests\Request;
use Core\Utils\Arr;

class Router
{
    private string $uri = '';
    private string $method;
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function __construct()
    {
        $this->loadRoutes();
        $this->run();
    }

    private function run()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];

        try {
            $this->callRoute();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function callRoute()
    {
        $routesToMatch = $this->routes[$this->method];

        [$isMatched, $route, $params] = (new MatchUri)($routesToMatch, $this->uri);

        if (!$isMatched) {
            throw new \Exception("Route {$this->uri} not found");
        }

        return $route->action($params);
    }

    private function loadRoutes()
    {
        require __DIR__ . '/../../src/routes/api.php';
    }

    # Registering routes

    private function register(string $method, string $uri, string $action)
    {
        $params = Request::getParams($uri);
        $route = new Route($uri, $action, $params);

        $this->routes[$method][] = $route;
    }

    public function get(string $uri, string $action)
    {
        $this->register('GET', $uri, $action);
    }

    public function post(string $uri, string $action)
    {
        $this->register('POST', $uri, $action);
    }
}