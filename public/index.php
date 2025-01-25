<?php

declare(strict_types=1);

use Kartalit\Routes\Router;
use Kartalit\Middlewares\AddJsonResponseHeader;

$app = require_once __DIR__ . '/../bootstrap.php';

$app->setBasePath($_ENV['ENV_SERVER_BASEPATH'] ?? '/');

$app->add(new AddJsonResponseHeader);

$app->group('', Router::class);

$app->run();
