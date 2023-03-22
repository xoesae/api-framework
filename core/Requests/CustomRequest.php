<?php

namespace Core\Requests;

class CustomRequest extends Request
{
    private const CONTENT_TYPE_FORM_DATA = 'multipart/form-data';
    private const CONTENT_TYPE_JSON = 'application/json';
    private ?string $contentType;

    public function __construct(
        private array $rules = [],
        private array $data = [],
    )
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? null;
        $this->contentType = $contentType ? explode(';', $_SERVER['CONTENT_TYPE'])[0] : null;

        $this->data = match ($this->contentType) {
            self::CONTENT_TYPE_FORM_DATA => $_POST,
            self::CONTENT_TYPE_JSON => json_decode(file_get_contents('php://input'), true),
            default => [],
        };
    }

    public function all(): array
    {
        return $this->data;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function validated(): array
    {
        // TODO: Implement validation
    }
}