<?php

namespace App\controllers;

use Firebase\JWT\Key;
use \App\database\Database;
use \App\http\Request;
use \App\http\Response;
use \DateTimeImmutable;
use \Firebase\JWT\JWT;

class AuthController
{
    public function login()
    {
        extract(Request::body());

        if (empty($email) && empty($password)) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }

        $db = Database::getInstance();
        $db->setTable('users');

        $user = $db->select("email = '$email'")->fetchObject('stdClass');

        if (!password_verify($password, $user->password)) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }

        $secretKey = getenv('SECRET_KEY');
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+6 minutes')->getTimestamp();
        $serverName = getenv('SERVER_NAME');
        $userEmail = $user->email;

        $data = [
            'iat' => $issuedAt->getTimestamp(), // Issued at: time when the token was generated
            'iss' => $serverName, // Issuer
            'nbf' => $issuedAt->getTimestamp(), // Not before
            'exp' => $expire, // Expire
            'userEmail' => $userEmail, // User name
        ];

        return JWT::encode($data, $secretKey, 'HS256');
    }

    public static function auth()
    {

        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            Response::send(400, [
                'status' => 'Bad Request',
                'message' => 'Token not found in request',
            ]);
            exit;
        }

        $jwt = $matches[1];

        if (empty($jwt)) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }

        $secretKey = getenv('SECRET_KEY');
        $now = new DateTimeImmutable();
        $token = self::decodeJwt($jwt, $secretKey);
        $serverName = getenv('SERVER_NAME');

        if (
            $token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp()
        ) {
            Response::send(401, [
                'status' => 'Unauthorized',
            ]);
            exit;
        }

        $db = Database::getInstance();
        $db->setTable('users');
        $user = $db->select("email = '$token->userEmail'")->fetchObject('stdClass');

        if (empty($user)) {
            Response::send(500, [
                'status' => 'Something is wrong',
            ]);
            exit;
        }

        $_COOKIE['auth_user'] = [
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    private static function decodeJwt($jwt, $secretKey)
    {
        try {
            return JWT::decode($jwt, new Key($secretKey, 'HS256'));
        } catch (\Throwable $th) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }
    }

    public function logout()
    {
        $_COOKIE['auth_user'] = [];
    }
}