<?php

return [
    'GET' => [
        '/' => 'PostController@index',
        '/user/[0-9]+' => 'UserController@show@auth',
    ],
    'DELETE' => [
        '/posts/[0-9]+' => 'PostController@delete',
        '/user/logout' => 'AuthController@logout@auth',
        '/user/delete/[0-9]+' => 'UserController@destroy@auth',
    ],
    'POST' => [
        '/user/register' => 'UserController@create',
        '/user/login' => 'AuthController@login',
    ],
    'PUT' => [
        'user/update/[0-9]+' => 'UserController@update@auth',
    ],
];