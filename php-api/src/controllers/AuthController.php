<?php

namespace App\controllers;

use App\database\Database;
use App\http\Request;
use \Firebase\JWT\JWT;

class AuthController
{
    public function login()
    {
        extract(Request::body());

        if (empty($email) && empty($password)) {
            throw new \Exception("Invalid credentials");
        }

        $db = Database::getInstance();
        $db->setTable('users');

        $user = $db->select("email = '$email'")->fetchObject('stdClass');

        if (!password_verify($password, $user->password)) {
            throw new \Exception("Invalid credentials");
        }

        $jwt_key = getenv('SECRET_KEY');

        $jwt = JWT::encode(['user' => $user->id], $jwt_key, 'HS256');

        $db->setTable('users_keys');

        $db->insert([
            'auth_key' => $jwt,
            'user_id' => $user->id,
        ]);

        return $jwt;
    }

    public function auth()
    {
        extract(Request::body());

        if (empty($jwt_key)) {
            throw new \Exception("Invalid credentials");
        }

        $db = Database::getInstance();
        $db->setTable('users_keys');

        $key = $db->select("auth_key = '$jwt_key'")->fetchObject('stdClass');

        $db->setTable('users');

        $user = $db->select("id = '$key->user_id'")->fetchObject('stdClass');

        return $user;
    }

    public function logout()
    {
        extract(Request::body());

        if (empty($jwt_key)) {
            throw new \Exception("Invalid credentials");
        }

        $db = Database::getInstance();
        $db->setTable('users_keys');
        return $db->delete("auth_key = '$jwt_key'");
    }
}