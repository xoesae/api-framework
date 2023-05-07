<?php

use JetBrains\PhpStorm\NoReturn;

if (! function_exists('var_dd')) {
    #[NoReturn]
    function var_dd(): void
    {
        var_dump(func_get_args());
        die();
    }
}