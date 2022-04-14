<?php

namespace App\models;

use \App\database\Database;

class UserKeys
{
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = Database::getInstance();
        $this->queryBuilder->setTable('users_keys');

    }
    public function store($key, $userId)
    {
        return $this->queryBuilder->insert([
            'auth_key' => $key,
            'user_id' => $userId,
        ]);
    }

    public function findById($id)
    {
        return $this->queryBuilder->select("user_id = '$id'")->fetchObject('stdClass');
    }

    public function destroy($id)
    {
        return $this->queryBuilder->delete("id = '$id'");
    }
}