<?php

namespace App\Routes;

class Route
{
    public function __construct(
        public string $uri,
        public string $action,
        public array $params = [],
    )
    {
        $uri = self::sanitizeUri($uri);
    }

    public function action(array $params = [])
    {
        [$class, $method] = explode('@', $this->action);
        $class = 'App\\Controllers\\' . $class;

        if (!(isset($class) && isset($method))) {
            throw new \Exception("Invalid action {$this->action}");
        }

        if (!class_exists($class)) {
            throw new \Exception("Class {$class} not found");
        }

        if (!method_exists($class, $method)) {
            throw new \Exception("Method $this->action not found");
        }

        $object = new $class();
        return $object->$method(...$params);
    }

    public function hasParams(): bool
    {
        return count($this->params) > 0;
    }

    public static function sanitizeUri(string $uri)
    {
        return (substr($uri, 0, 1) === '/') ? substr($uri, 1) : $uri;
    }
}