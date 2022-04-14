<?php

namespace App\controllers;

use \App\database\Database;
use \App\http\Request;
use \App\http\Response;
use \App\models\UserKeys;
use \App\models\UserModel;
use \DateTimeImmutable;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

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

        $user = $db->select("email = '$email'")->fetchObject(UserModel::class);

        if (!password_verify($password, $user->password)) {
            Response::send(400, [
                'status' => 'Bad Request',
            ]);
            exit;
        }

        $secretKey = getenv('SECRET_KEY');
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+30 minutes')->getTimestamp();
        $serverName = getenv('SERVER_NAME');
        $userEmail = $user->email;

        $data = [
            'iat' => $issuedAt->getTimestamp(), // Issued at: time when the token was generated
            'iss' => $serverName, // Issuer
            'nbf' => $issuedAt->getTimestamp(), // Not before
            'exp' => $expire, // Expire
            'userEmail' => $userEmail, // User name
        ];

        $key = JWT::encode($data, $secretKey, 'HS256');

        $userKeys = new UserKeys();
        $userKeys->store($key, $user->id);

        Response::send(200, [
            'key' => $key,
        ]);
        exit;
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
        $user = $db->select("email = '$token->userEmail'")->fetchObject(UserModel::class);

        if (empty($user)) {
            Response::send(500, [
                'status' => 'Something is wrong',
            ]);
            exit;
        }

        $userKeys = new UserKeys();
        $key = $userKeys->findById($user->id);

        if (empty($key)) {
            Response::send(401, [
                'status' => 'Unauthorized',
            ]);
            exit;
        }

        $_COOKIE['user_key'] = $token;
        $_COOKIE['auth_user'] = $user->id;
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
        $userId = $_COOKIE['auth_user'];

        $userKeys = new UserKeys();
        $key = $userKeys->findById($userId);

        if (empty($key)) {
            Response::send(500, [
                'status' => 'Something is wrong',
            ]);
            exit;
        }

        if ($userKeys->destroy($key->id)) {
            Response::send(200, [
                'status' => 'Success',
            ]);
            exit;

        }

        Response::send(401, [
            'status' => 'Unauthorized',
        ]);
        exit;

    }
}