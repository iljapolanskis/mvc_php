<?php

namespace MVC\Core;

class Request
{
    public function __construct() {}

    public function path(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        return $position === false ? $path : substr($path, 0, $position);
    }

    public function isPath(string $path): bool
    {
        return $this->path() === $path;
    }

    public function params(): array
    {
        return $this->method() === HTTP::METHOD_GET ? filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS) : [];
    }

    public function postData(): array
    {
        return $this->method() === HTTP::METHOD_POST ? filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) : [];
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? HTTP::METHOD_GET;
    }

    public function isGet(): bool
    {
        return $this->method() === HTTP::METHOD_GET;
    }

    public function isPost(): bool
    {
        return $this->method() === HTTP::METHOD_POST;
    }
}