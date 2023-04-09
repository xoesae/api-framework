<?php

namespace Core\Routes\Traits;

use Core\Routes\Route;
use Core\Utils\Arr;

trait MatchUri
{
    protected static ?Route $matchedRoute = null;
    protected static bool $isMatched = false;

    private static function endMatch(Route $route): void
    {
        self::$isMatched = true;
        self::$matchedRoute = $route;
    }

    public static function matchUri(array $routesToMatch, string $path): array
    {
        $paths = Arr::explodeWithoutEmptyValues('/', $path);
        $params = [];

        foreach ($routesToMatch as $route) {
            if ($route->uri === $path) {
                self::endMatch($route);
                break;
            }

            $routePaths = Arr::explodeWithoutEmptyValues('/', $route->uri);

            if (count($routePaths) !== count($paths)) {
                continue;
            }

            $firstPathsAreEqual = $routePaths[0] === $paths[0];

            if ($firstPathsAreEqual && $route->hasParams()) {

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
                    self::endMatch($route);
                    break;
                }
            }
        }

        return $params;
    }
}