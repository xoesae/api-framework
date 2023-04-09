<?php

namespace Core\Routes;

use Core\Requests\Request;
use Core\Routes\Traits\ControllerGroup;
use Core\Routes\Traits\LoadRoutes;
use Core\Routes\Traits\MatchUri;
use Core\Utils\Arr;
use Exception;

class Router
{
    use LoadRoutes, MatchUri, ControllerGroup;

    private string $uri = '';
    private string $method;
    private static array $routes = [
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

    private function run(): void
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];

        try {
            $this->callRoute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @throws Exception
     */
    private function callRoute(): void
    {
        $routesToMatch = self::$routes[$this->method];

        $parameters = self::matchUri($routesToMatch, $this->uri);

        if (!self::$isMatched) {
            throw new Exception("Route {$this->uri} not found");
        }

        self::$matchedRoute->run($parameters);
    }

    private static function instanceRoute(string $uri, string $action): Route
    {
        $params = Request::getParams($uri);

        return new Route($uri, $action, $params);
    }

    # Registering routes

    private static function addRoute(string $method, string $uri, string $action): void
    {
        if (self::isCalledByControllerGroup()) {
            $controllerNamespace = self::getControllerNamespace();
            $action = $controllerNamespace . '@' . $action;
        }

        $route = self::instanceRoute($uri, $action);

        self::$routes[$method][] = $route;
    }

    public static function get(string $uri, string $action): void
    {
        self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, string $action): void
    {
        self::addRoute('POST', $uri, $action);
    }

    public static function put(string $uri, string $action): void
    {
        self::addRoute('PUT', $uri, $action);
    }

    public static function delete(string $uri, string $action): void
    {
        self::addRoute('DELETE', $uri, $action);
    }

    public static function controller(string $controller, \Closure $routes): void
    {
        self::initControllerGroup($controller);

        $routes();

        self::endControllerGroup();
    }
}