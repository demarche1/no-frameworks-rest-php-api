<?php

namespace App\controllers;

class PostController
{
    function index()
    {
        return [
            'title' => 'Index Test route',
            'date' => date('D/m/y')
        ];
    }

    function show($params)
    {
        return [
            'title' => 'Test show route',
            'date' => date('D/m/y')
        ];
    }

    function delete($params)
    {
        return [
            'title' => 'Test delete route',
            'date' => date('D/m/y')
        ];
    }
}