<?php

namespace App\core;

use \App\http\Response;
use \App\router\Router;

class Server
{
    public static function start()
    {
        try {
            $routes = require dirname(__DIR__, 1) . '/router/routes.php';

            $router = new Router($routes);

            echo Response::send(201, $router->init());

        } catch (\Exception $e) {
            echo Response::send(500, [
                'error' => $e->getMessage(),
            ]);

            exit;
        }
    }
}