<?php

require_once __DIR__ . '/bootstrap.php';

use \App\core\Environment;
use \App\core\Server;

Environment::loadEnv(__DIR__);

Server::start();