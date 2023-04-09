<?php

namespace Core\Routes;

use Closure;
use Core\Requests\Request;
use Core\Routes\Traits\Group;
use Core\Routes\Traits\LoadRoutes;
use Core\Routes\Traits\MatchUri;
use Exception;

class Router
{
    use LoadRoutes, MatchUri, Group;

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

    /**
     * @throws Exception
     */
    private static function instanceRoute(string $uri, string $action): Route
    {
        $params = Request::getParams($uri);

        return new Route($uri, $action, $params);
    }

    # Registering routes

    private static function addRoute(string $method, string $uri, string $action): void
    {
        if (self::$isGroup) {
            self::resolveGroupRoute($uri, $action);
        }

       try {
           $route = self::instanceRoute($uri, $action);

           self::$routes[$method][] = $route;
       } catch (Exception $e) {
           echo $e->getMessage();
       }
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

    # Grouping routes

    public static function group(array $options, Closure $routes): void
    {
        self::initGroup($options);

        $routes();

        self::endGroup();
    }

    public static function controller(string $controller, Closure $routes): void
    {
        self::group([
            'controller' => $controller,
        ], $routes);
    }
}