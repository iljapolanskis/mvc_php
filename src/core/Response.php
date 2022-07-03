<?php

namespace MVC\Core;

class Response
{
    public function redirect(string $path): void
    {
        header("Location: $path");
    }

    public function setStatusCode(int $statusCode)
    {
        http_response_code($statusCode);
    }
}