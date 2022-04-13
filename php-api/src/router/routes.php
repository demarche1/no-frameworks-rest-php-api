<?php

return [
    'GET' => [
        '/' => 'PostController@index@auth',
        '/posts/[0-9]+' => 'PostController@show@auth',
    ],
    'DELETE' => [
        '/posts/[0-9]+' => 'PostController@delete',
        '/user/logout' => 'AuthController@logout@auth',
    ],
    'POST' => [
        '/user/register' => 'UserController@create',
        '/user/login' => 'AuthController@login',
    ],
];