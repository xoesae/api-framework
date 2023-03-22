<?php

namespace Core\Utils;

class Hash
{
    public static function make(string $string): string
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public static function verify(string $string, string $hash): bool
    {
        return password_verify($string, $hash);
    }
}