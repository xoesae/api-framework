<?php

namespace Core\Routes;

use Core\Containers\Container;
use Core\Controllers\Controller;
use Exception;

class Route
{
    public static string $separator = '@';
    private static string $controllerNamespace = 'App\\Controllers\\';
    public string $uri;
    public array $params = [];

    private Controller $controller;
    private string $method;

    /**
     * @throws Exception
     */
    public function __construct(
        string $uri,
        string $action,
        array $params = [],
    ) {
        $this->uri = $uri;
        $this->params = $params;

        [$class, $method] = self::explodeAction($action);

        self::validateAction($class, $method);

        $this->controller = self::instanceController($class);
        $this->method = $method;
    }

    public static function getFullNamespaceControllerClass(string $class): string
    {
        $hasFullNamespace = str_contains($class, self::$controllerNamespace);

        if (!$hasFullNamespace) {
            $class = self::$controllerNamespace . $class;
        }

        return $class;
    }

    public static function explodeAction(string $action): array
    {
        [$class, $method] = explode(self::$separator, $action);
        $class = self::getFullNamespaceControllerClass($class);

        return [$class, $method];
    }

    /**
     * @throws Exception
     */
    public static function validateAction(string $class, string $method): void
    {
        $formattedAction = $class . self::$separator . $method;

        if (!isset($method)) {
            throw new Exception("Invalid action {$formattedAction}");
        }

        if (!class_exists($class)) {
            throw new Exception("Class {$class} not found");
        }

        if (!method_exists($class, $method)) {
            throw new Exception("Method {$formattedAction} not found");
        }
    }

    /**
     * @throws Exception
     */
    public static function instanceController(string $class): mixed
    {
        $container = Container::getInstance();

        return $container->get($class);
    }

    public static function resolveMethodParameters(string $class, string $method, array $uriParameters = []): array
    {
        $container = Container::getInstance();

        $parameters = $container->resolveParameters($class, $method);

        return array_merge($parameters, $uriParameters);
    }

    /**
     * @throws Exception
     */
    public function run(array $uriParameters = []): mixed
    {
        $controller = $this->controller;
        $class = get_class($controller);
        $method = $this->method;

        $parameters = self::resolveMethodParameters($class, $method, $uriParameters);

        return $controller->$method(...$parameters);
    }

    public function hasParams(): bool
    {
        return count($this->params) > 0;
    }

    public static function sanitizeUri(string $uri): string
    {
        return (str_starts_with($uri, '/')) ? substr($uri, 1) : $uri;
    }
}