<?php

namespace App\controllers;

use \App\http\Request;
use \App\http\Response;
use \App\models\UserModel;

class UserController
{
    public function index($params)
    {
        return [
            'title' => 'Index Test route',
            'date' => date('D/m/y'),
            'params' => $params,
        ];
    }

    public function create()
    {
        extract(Request::body());

        if (empty($email) || empty($name) || empty($password)) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }

        $user = new UserModel();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;

        if (!$user->save()) {
            Response::send(500, [
                'status' => 'Something was wrong',
            ]);
            exit;
        }

        Response::send(201, [
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
        exit;
    }

    public function show($params)
    {
        if (empty($params)) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }

        $userModel = new UserModel();

        $user = $userModel->findById($params['user']);

        if (!$user) {
            Response::send(200, [
                'data' => [],
            ]);

            exit;
        }

        Response::send(200, [
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
        exit;
    }

    public function update($params)
    {
        $userModel = new UserModel();

        $user = $userModel->findById($params['update']);

        $cookieEmail = $_COOKIE['user_key']->userEmail;

        if ($user->email !== $cookieEmail) {
            Response::send(401, [
                'status' => 'Unauthorized',
            ]);
            exit;
        }

        extract(Request::body());

        if (!$user instanceof UserModel) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }

        $user->email = empty($email) ? $user->email : $email;
        $user->name = empty($name) ? $user->name : $name;
        $user->password = empty($password) ? $user->password : $password;

        if (!$user->update($user->id)) {
            Response::send(500, [
                'status' => 'Something was wrong',
            ]);
            exit;
        }

        Response::send(200, [
            'status' => 'Success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function destroy($params)
    {
        $userModel = new UserModel();

        $user = $userModel->findById($params['delete']);

        $cookieEmail = $_COOKIE['user_key']->userEmail;

        if ($user->email !== $cookieEmail) {
            Response::send(401, [
                'status' => 'Unauthorized',
            ]);
            exit;
        }

        if (!$user->delete()) {
            Response::send(500, [
                'status' => 'Something was wrong',
            ]);
            exit;
        }

        Response::send(200, [
            'status' => 'Success',
        ]);
        exit;
    }
}