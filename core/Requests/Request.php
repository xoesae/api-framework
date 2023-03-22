<?php

namespace Core\Requests;

use Core\Utils\Arr;

class Request
{
    public static function getParsedUri(): array
    {
        return parse_url($_SERVER['REQUEST_URI']);
    }

    public static function getQuery(): array
    {
        parse_str(self::getParsedUri(), $query);

        return $query;
    }

    public static function getPath(): string
    {
        return self::getParsedUri()['path'];
    }

    /**
     * Get params from uri
     * URI pattern: /foo/:bar
     * return: [ 1 => 'bar']
     */
    public static function getParams(string $uri): array
    {
        $paths = Arr::removeEmptyValues(explode('/', $uri));
        $params = [];

        foreach ($paths as $position => $path) {
            if (substr($path, 0, 1) === ':') {
                $params = [
                    ...$params,
                    $position => substr($path, 1),
                ];
            }
        }

        return $params;
    }
}