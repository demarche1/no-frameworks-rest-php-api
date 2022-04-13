<?php

namespace App\models;

use App\database\Database;

class UserModel
{
    public $id;
    public $name;
    public $email;
    public $password;
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = Database::getInstance();
        $this->queryBuilder->setTable('users');
    }

    public function save()
    {
        try {
            $this->id = $this->queryBuilder->insert([
                'name' => $this->name,
                'email' => $this->email,
                'password' => password_hash($this->password, PASSWORD_ARGON2I),
            ]);

            return true;

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function findById($id)
    {
        return $this->queryBuilder->select("id = '$id'")->fetchObject(self::class);
    }

}