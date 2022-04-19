<?php

use \App\controllers\AuthController;

function controller($matchedUri, $params)
{
    [$controller, $method, $isAuth] = explode('@', array_values($matchedUri)[0]);

    if (!empty($isAuth)) {
        AuthController::auth();
    }

    $controllerNamespace = CONTROLLER_PATH . $controller;

    if (!class_exists($controllerNamespace)) {
        throw new Exception("Class does not exists");
    }

    $controllerIntance = new $controllerNamespace;

    return $controllerIntance->$method($params);
}