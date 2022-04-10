<?php

use \Exception;
function controller($matchedUri, $params)
{
    [$controller, $method] = explode('@', array_values($matchedUri)[0]);
    $controllerNamespace = CONTROLLER_PATH . $controller;

    if (!class_exists($controllerNamespace)) {
        throw new Exception("Class does not exists");
    }

    $controllerIntance = new $controllerNamespace;

    return $controllerIntance->$method($params);
}