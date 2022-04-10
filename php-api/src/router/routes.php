<?php

return [
    'GET' => [
        '/' => 'PostController@index',
        '/posts/[0-9]+' => 'PostController@show',

    ],
    'DELETE' => [
        '/posts/[0-9]+' => 'PostController@delete',
    ],
];