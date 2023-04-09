<?php

namespace Core\Routes\Traits;

use Core\Routes\Route;

trait ControllerGroup
{
    private static bool $isGroup = false;
    private static bool $isControllerGroup = false;
    private static ?string $controllerNamespace = null;

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

        $controller = $options['controller'] ?? null;

        if ($controller) {
            self::initControllerGroup($controller);
        }
    }

    private static function endGroup(): void
    {
        self::$isGroup = false;
        self::endControllerGroup();
    }

    public static function resolveRoute(string $action): string
    {
        if (self::$isControllerGroup) {
            $controllerNamespace = self::getControllerNamespace();
            $action = $controllerNamespace . Route::$separator . $action;
        }

        return $action;
    }

}