<?php

namespace Core\Routes\Traits;

trait LoadRoutes
{
    private static string $pathToRoutes = __DIR__ . '/../../../src/routes/api.php';

    private function loadRoutes(): void
    {
        require self::$pathToRoutes;
    }
}