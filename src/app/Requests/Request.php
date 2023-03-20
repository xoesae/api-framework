<?php

namespace App\Requests;

use App\Utils\Arr;

class Request
{
    private const CONTENT_TYPE_FORM_DATA = 'multipart/form-data';
    private const CONTENT_TYPE_JSON = 'application/json';
    private ?string $contentType;
    private array $data;

    public function __construct()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? null;
        $this->contentType = $contentType ? explode(';', $_SERVER['CONTENT_TYPE'])[0] : null;

        $this->data = match ($this->contentType) {
            self::CONTENT_TYPE_FORM_DATA => $_POST,
            self::CONTENT_TYPE_JSON => json_decode(file_get_contents('php://input'), true),
            default => [],
        };
    }

    public static function getParsedUri(): array
    {
        return parse_url($_SERVER['REQUEST_URI']);
    }

    public static function getQuery(): array
    {
        parse_str(self::getParsedUri(), $query);

        return $query;
    }

    public static function getPath(): string
    {
        return self::getParsedUri()['path'];
    }

    /**
     * Get params from uri
     * URI pattern: /foo/:bar
     * return: [ 1 => 'bar']
     */
    public static function getParams(string $uri): array
    {
        $paths = Arr::removeEmptyValues(explode('/', $uri));
        $params = [];

        foreach ($paths as $position => $path) {
            if (substr($path, 0, 1) === ':') {
                $params = [
                    ...$params,
                    $position => substr($path, 1),
                ];
            }
        }

        return $params;
    }

    public function all(): array
    {
        return $this->data;
    }
}