<?php

namespace App\core;

use \App\router\Router;
use \Exception;

class Server
{
    public static function start()
    {
        try {
            $routes = require dirname(__DIR__, 1) . '/router/routes.php';

            $router = new Router($routes);

            $router->init();

        } catch (Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            exit;
        }
    }
}