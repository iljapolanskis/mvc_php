<?php

namespace MVC\Core;


class Application
{

    public static Application $app;
    public static string $ROOT_DIR;

    public Session $session;
    public Database $db;
    public Request $request;
    public Response $response;
    public Router $router;

    public function __construct(string $root, array $config)
    {
        self::$app = $this;
        self::$ROOT_DIR = $root . "/src";

        $this->session = new Session();
        $this->db = new Database($config['db']);
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
    }

    public function run(): void
    {
        echo $this->router->resolve();
    }

    public function view(): void {}
}
