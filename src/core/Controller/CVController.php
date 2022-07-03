<?php

namespace MVC\Core\Controller;

use MVC\Core\Attributes\Route;
use MVC\Core\Controller\Abstract\Controller;

class CVController extends Controller
{
    #[Route('/')]
    public static function index()
    {
        echo "Hello Controller!";
    }
}