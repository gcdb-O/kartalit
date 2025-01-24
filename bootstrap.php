<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->load();

$container = require_once __DIR__ . '/src/Config/container.php';
AppFactory::setContainer($container);

return AppFactory::create();
