<?php

namespace Core\Routes;

use Core\Containers\Container;
use Exception;
use ReflectionMethod;

class Route
{
    private static string $separator = '@';
    private static string $controllerNamespace = 'App\\Controllers\\';

    public function __construct(
        public string $uri,
        public string $action,
        public array $params = [],
    ) {}

    public static function getFullNamespaceControllerClass(string $class): string
    {
        return self::$controllerNamespace . $class;
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
        [$class, $method] = self::explodeAction($this->action);

        self::validateAction($class, $method);

        $controller = self::instanceController($class);

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