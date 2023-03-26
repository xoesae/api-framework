<?php

if (! function_exists('var_dd')) {
    function var_dd() {
        var_dump(func_get_args());
        die();
    }
}