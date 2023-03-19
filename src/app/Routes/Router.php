<?php

namespace App\Routes;

use App\Requests\Request;
use App\Utils\Arr;

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

    private function matchUri(): array
    {
        $path = Request::getPath();
        $paths = Arr::explodeWithoutEmptyValues('/', $path);
        $routes = $this->routes[$this->method];
        $params = [];
        $matchedRoute = null;
        $isMatched = false;

        foreach ($routes  as $route) {
            if ($route->uri === $path) {
                $isMatched = true;
                $matchedRoute = $route;
                break;
            }

            $routePaths = Arr::explodeWithoutEmptyValues('/', $route->uri);

            if (count($routePaths) !== count($paths)) {
                continue;
            }

            
            if (($routePaths[0] === $paths[0]) && $route->hasParams()) {  

                foreach ($route->params as $position => $param) {
                    $params = [
                        ...$params,
                        $param => $paths[$position],
                    ];
                    unset($paths[$position], $routePaths[$position]);
                }

                $uriWithoutParams = implode('/', $paths);
                $routeWithoutParams = implode('/', $routePaths);

                if ($uriWithoutParams === $routeWithoutParams) {
                    $isMatched = true;
                    $matchedRoute = $route;
                    break;
                }
            }
        }

        return [
            $isMatched,
            $matchedRoute,
            $params,
        ];
    }

    private function callRoute()
    {
        [$isMatched, $route, $params] = $this->matchUri();

        if (!$isMatched) {
            throw new \Exception("Route {$this->uri} not found");
        }

        return $route->action($params);
    }

    private function loadRoutes()
    {
        require __DIR__ . '/../../routes/api.php';
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
}