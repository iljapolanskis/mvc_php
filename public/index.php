<?php

use MVC\Core\Application;
use MVC\Core\Controller\AuthController;
use MVC\Core\Controller\CMSController;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'name' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASS'],
    ],
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [CMSController::class, 'index']);

$app->router->get('/features', [CMSController::class, 'features']);

$app->router->get('/home', [CMSController::class, 'home']);

$app->router->get('/contact', [CMSController::class, 'contact']);
$app->router->post('/contact', [CMSController::class, 'postContact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->run();

