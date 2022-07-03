<?php

namespace MVC\Core\Controller\Abstract;

use MVC\Core\Application;
use MVC\Core\Response;
use MVC\Core\Router;
use MVC\Core\Session;

abstract class Controller
{
    protected Router $router;
    protected Response $response;
    protected Session $session;
    public string $layout = "default";

    public function __construct()
    {
        $this->router = Application::$app->router;
        $this->response = Application::$app->response;
        $this->session = Application::$app->session;
    }

    public function render(string $view, $params = []): bool|string
    {
        return $this->router->render($view, $params, $this->layout);
    }

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }
}