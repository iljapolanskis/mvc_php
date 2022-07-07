<?php

namespace MVC\Core;

class Navigation
{
    public function __construct(
        public Request $request,
    ) {}

    public function begin()
    {
        return '<nav class="nav nav-masthead justify-content-center float-md-end">';
    }

    public function home(string $path, string $label)
    {
        $active = $this->request->isPath($path) || $this->request->isPath('/') ? 'active' : '';
        return <<<HTML
            <a class="nav-link fw-bold py-1 px-0 $active" aria-current="page" href="$path">$label</a>
HTML;

    }

    public function item(string $path, string $label)
    {
        $active = $this->request->isPath($path) ? 'active' : '';
        return <<<HTML
            <a class="nav-link fw-bold py-1 px-0 $active" aria-current="page" href="$path">$label</a>
HTML;
    }

    public function end()
    {
        return '</nav>';
    }
}