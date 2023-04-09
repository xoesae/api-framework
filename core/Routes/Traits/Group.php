<?php

namespace Core\Routes\Traits;

use Core\Routes\Route;

trait Group
{
    private static bool $isGroup = false;
    private static bool $isControllerGroup = false;
    private static ?string $controllerNamespace = null;
    private static ?string $prefix = null;

    public static function isCalledByControllerGroup(): bool
    {
        return self::$isControllerGroup;
    }

    private static function initControllerGroup(string $controller): void
    {
        self::$isControllerGroup = true;
        self::$controllerNamespace = $controller;
    }

    private static function endControllerGroup(): void
    {
        self::$isControllerGroup = false;
        self::$controllerNamespace = null;
    }

    public static function getControllerNamespace(): ?string
    {
        return self::$controllerNamespace;
    }

    private static function initGroup(array $options): void
    {
        self::$isGroup = true;

        // Prefix
        $prefix = $options['prefix'] ?? null;
        if ($prefix) {
            self::$prefix = str_ends_with($prefix, '/') ? substr($prefix, 0, -1) : $prefix;
        }

        // Controller
        $controller = $options['controller'] ?? null;
        if ($controller) {
            self::initControllerGroup($controller);
        }
    }

    private static function endGroup(): void
    {
        self::$isGroup = false;

        // Prefix
        self::$prefix = null;

        // Controller
        self::endControllerGroup();
    }

    public static function resolveGroupRoute(string &$uri, string &$action): void
    {
        if (self::$isControllerGroup) {
            $controllerNamespace = self::getControllerNamespace();
            $action = $controllerNamespace . Route::$separator . $action;
        }

        $prefix = self::$prefix;

        if ($prefix) {
            $uri = str_starts_with($uri, '/') ? $uri : "/{$uri}";

            $uri = $prefix . $uri;
        }
    }
}