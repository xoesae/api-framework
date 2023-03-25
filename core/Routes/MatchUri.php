<?php

namespace Core\Routes;

use Core\Requests\Request;
use Core\Utils\Arr;

class MatchUri
{
    public function __invoke(array $routesToMatch, string $path): array
    {
        $paths = Arr::explodeWithoutEmptyValues('/', $path);
        $params = [];
        $matchedRoute = null;
        $isMatched = false;

        foreach ($routesToMatch as $route) {
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
}