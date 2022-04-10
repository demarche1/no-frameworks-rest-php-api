<?php

namespace App\core;

class Environment
{
    public static function loadEnv($path)
    {
        if (!file_exists($path . '/.env')) {
            return;
        }

        $rows = file($path . '/.env');

        foreach ($rows as $envVariable) {
            putenv(trim($envVariable));
        }
    }
}