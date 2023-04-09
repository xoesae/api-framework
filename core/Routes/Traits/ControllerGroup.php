<?php

namespace Core\Routes\Traits;

trait ControllerGroup
{
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
}