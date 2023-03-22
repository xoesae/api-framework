<?php

namespace Core\Utils;

class Arr
{
    public static function removeEmptyValues(array $arr)
    {
        return array_values(array_filter($arr));
    }

    public static function explodeWithoutEmptyValues(string $separator, string $string, int $limit = PHP_INT_MAX)
    {
        return self::removeEmptyValues(explode($separator, $string, $limit));
    }
}