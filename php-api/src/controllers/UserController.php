<?php

namespace App\controllers;

use \App\database\Database;

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
        $db = Database::getInstance();
        $db->setTable('users');

        return $db->insert([
            'name' => 'Alessandro',
            'email' => 'ale@email.com',
            'password' => password_hash('123', PASSWORD_ARGON2I),
        ]);
    }

    public function show($params)
    {
        return [
            'title' => 'Test show route',
            'date' => date('D/m/y'),
        ];
    }

    public function delete($params)
    {
        return [
            'title' => 'Test delete route',
            'date' => date('D/m/y'),
        ];
    }
}