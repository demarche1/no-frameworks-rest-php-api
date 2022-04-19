<?php

function createLog($log)
{
    $path = dirname(__DIR__, 2);

    file_put_contents($path . '/logs.txt', $log, FILE_APPEND);
}