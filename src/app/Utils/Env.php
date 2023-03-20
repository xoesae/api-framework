<?php

namespace App\Utils;

class Env {
    private const ENV_FILE_PATH = __DIR__ . '/../../../.env';

    public static function set(): void
    {
        $file = fopen(self::ENV_FILE_PATH, 'r') or die ('Unable to open file');
        $content = fread($file, filesize(self::ENV_FILE_PATH));
        $rows = explode(PHP_EOL, $content);
        
        foreach ($rows as $row) {
            putenv($row);
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return $value;
    }
}