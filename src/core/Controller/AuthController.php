<?php

namespace MVC\Core\Controller;

use InvalidArgumentException;
use MVC\Core\Application;
use MVC\Core\Controller\Abstract\Controller;
use MVC\Core\HTTP;
use MVC\Core\Models\Auth\User;
use MVC\Core\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->setLayout('other');

        $user = new User();

        if ($request->isPost()) {
            $credentials = $request->postData();
            $user->populate($credentials);
            if ($user->login()) {
                $this->session->createFlash('success', 'You are now logged in');
                $this->response->redirect('/');
            }
            $this->session->createFlash('error', 'Invalid credentials');
        }

        return $this->render('login', [
            'model' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $this->setLayout('other');

        $user = new User();

        if ($request->isPost()) {
            $credentials = $request->postData();
            try {
                $user->populate($credentials);
                if ($user->save()) {
                    $this->session->createFlash('success', 'User created successfully');
                    $this->response->redirect('home');
                }
            } catch (InvalidArgumentException $e) {
                return $this->render('register', [
                    "model" => $user,
                ]);
            }
        }

        return $this->render('register', [
            "model" => $user,
        ]);
    }

}