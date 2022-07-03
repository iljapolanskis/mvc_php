<?php

namespace MVC\Core;

use MVC\Core\Attributes\Route;
use MVC\Core\Controller\Abstract\Controller;


class Router
{
    private Controller $controller;

    public function __construct(
        protected Response $response = new Response(),
        protected Request $request = new Request(),
        protected array $routes = [],
    ) {}

    public function resolve()
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? null;

        if (is_null($callback)) {
            $this->response->setStatusCode(HTTP::RESOURCE_NOT_FOUND);
            return $this->renderContent("404 Page Not Found");
        }

        if (is_string($callback)) {
            return $this->renderViewTemplate($callback);
        }

        if (is_array($callback)) {
            $this->controller = new $callback[0]();
            $callback[0] = $this->controller;
        }

        return call_user_func($callback, $this->request);
    }

    public function get(string $path, callable|string|array $callback): void
    {
        $this->register($path, $callback, HTTP::METHOD_GET);
    }

    public function post(string $path, callable|string|array $callback): void
    {
        $this->register($path, $callback, HTTP::METHOD_POST);
    }

    public function register(string $path, callable|string|array $callback, string $method): void
    {
        $this->routes[$method][$path] = $callback;
    }

    public function renderViewTemplate(string $template, array $params = []): array|bool|string
    {
        $content = str_replace('{{ content }}', $this->view($template), $this->layout($this->controller->getLayout()));
        return $content;
    }

    public function renderContent(string $content): array|bool|string
    {
        $layout = isset($this->controller) ? $this->layout($this->controller->getLayout()) : $this->layout();
        return str_replace('{{ content }}', $content, $layout);
    }


    public function render(string $template, array $params, string $layout = 'default'): bool|string
    {
        ob_start();
        $layout = $this->layout($layout);
        $view = $this->view($template, $params);
        return str_replace('{{ content }}', $view, $layout);
    }

    protected function layout(string $layout = 'default'): bool|string
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.phtml";
        return ob_get_clean();
    }


    public function view($template, array $params): bool|string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$template.phtml";
        return ob_get_clean();
    }

    public function registerRoutesFromControllerAttributes(array $controllers)
    {
        foreach ($controllers as $controller) {
            $reflectionController = new \ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    /* @var $route Route */
                    $route = $attribute->newInstance();
//                    $this->register($route->path, $route->method, );
                }
            }
        }
    }
}