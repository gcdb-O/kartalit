<?php

declare(strict_types=1);

use DI\Container;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../../vendor/autoload.php';

Dotenv::createImmutable(__DIR__ . '/../../')->load();

/** @var Container $container */
$container = require_once __DIR__ . '/container.php';
AppFactory::setContainer($container);

return AppFactory::create();
