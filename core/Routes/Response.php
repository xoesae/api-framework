<?php

namespace Core\Routes;

class Response
{
    public static function json(mixed $data = [], int $code = 200): void
    {
        http_response_code($code);
        echo json_encode($data);
    }
}