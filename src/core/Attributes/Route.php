<?php

namespace MVC\Core\Attributes;


use Attribute;
use MVC\Core\HTTP;

#[Attribute]
class Route
{
    public function __construct(public string $path, public string $method = HTTP::METHOD_GET) {}
}