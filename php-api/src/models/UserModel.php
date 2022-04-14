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

    public function update($id)
    {
        try {
            $hashInfo = password_get_info($this->password);

            return $this->queryBuilder->update("id = '$id'", [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $hashInfo['algoName'] === 'unknown' ? password_hash($this->password, PASSWORD_ARGON2I) : $this->password,
            ]);

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function delete()
    {
        try {
            return $this->queryBuilder->delete("id = '$this->id'");
        } catch (\Throwable $th) {
            return false;
        }
    }
}