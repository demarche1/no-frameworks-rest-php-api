<?php

namespace App\http;

class Request
{
    public static function body()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}