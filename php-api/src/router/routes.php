<?php

return [
    'GET' => [
        '/' => 'PostController@index',
        '/users/[0-9]+' => 'UserController@show@auth',
    ],
    'DELETE' => [
        '/posts/[0-9]+' => 'PostController@delete',
        '/user/logout' => 'AuthController@logout@auth',
    ],
    'POST' => [
        '/user/register' => 'UserController@create@auth',
        '/user/login' => 'AuthController@login',
    ],
];