<?php

namespace MVC\Core\Form;

use MVC\Core\Models\Abstract\Model;

class Form
{
    public function __construct() {}

    public function begin(string $action, string $method = "post"): string
    {
        return "<form action='{$action}' method='{$method}'>";
    }

    public function field(Model $model, string $attribute): Field
    {
        return new Field($model, $attribute);
    }

    public function submit(string $label): string
    {
        return <<<HTML
        <button type="submit" class="btn px-4 btn-primary">{$label}</button>
        HTML;
    }

    public function end(): string
    {
        return "</form>";
    }
}