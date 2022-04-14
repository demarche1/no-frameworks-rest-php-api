<?php

function log($log) {
    $path = dirname(__DIR__, 2);

    if(file_exists($path, '/logs.txt')) {
        file_put_contents('/logs.txt', $log);
    }
}