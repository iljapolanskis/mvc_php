<?php

namespace MVC\Core\Controller;

use MVC\Core\Application;
use MVC\Core\Controller\Abstract\Controller;
use MVC\Core\Request;

class CMSController extends Controller
{
    public function index()
    {
        return $this->router->render('home');
    }

    public function home()
    {
        $params = [
            "place" => "Riga, Sarkandaugava",
        ];
        return $this->render('home', $params);
    }

    public function features()
    {
        $params = [
            "place" => "Riga, Sarkandaugava",
        ];
        return $this->render('features', $params);
    }

    public function contact()
    {
        $params = [
            "place" => "Riga, Sarkandaugava",
        ];
        return $this->render('contact', $params);
    }

    public function postContact(Request $request)
    {
        $postData = $request->postData();

    }
}