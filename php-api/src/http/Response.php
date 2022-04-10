<?php

namespace App\http;

class Response
{
    private const CONTENT_TYPE = 'application/json';

    private static function sendHeaders($httpCode)
    {
        http_response_code($httpCode);
        header("Content-Type: " . self::CONTENT_TYPE);
    }

    public static function send($httpCode, $content)
    {
        self::sendHeaders($httpCode);
        return json_encode([
            'data' => $content,
        ], JSON_FORCE_OBJECT);
    }
}