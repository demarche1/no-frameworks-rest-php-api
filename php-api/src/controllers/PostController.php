<?php

namespace App\controllers;

class PostController
{
    public function index($params)
    {
        echo '<pre>';
        print_r(json_decode(file_get_contents('php://input'), true));
        echo '</pre>';
        exit;
        return [
            'title' => 'Index Test route',
            'date' => date('D/m/y'),
            'params' => $params,
        ];
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