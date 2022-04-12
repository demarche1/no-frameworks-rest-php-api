<?php

return [
    'GET' => [
        '/' => 'PostController@index',
        '/posts/[0-9]+' => 'PostController@show@auth',
    ],
    'DELETE' => [
        '/posts/[0-9]+' => 'PostController@delete',
        '/user/logout' => 'AuthController@logout',
    ],
    'POST' => [
        '/user/register' => 'UserController@create',
        '/user/login' => 'AuthController@login',
    ],
];