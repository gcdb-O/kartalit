<?php

declare(strict_types=1);

use Kartalit\Config\Config;
use Kartalit\Errors\ErrorHandler;
use Kartalit\Middlewares\AddResponseHeaders;
use Kartalit\Middlewares\ReadUserFromToken;
use Kartalit\Routes\Router;
use Slim\App;


/** @var App $app */
$app = require_once __DIR__ . '/../src/Config/bootstrap.php';

$envConfig = new Config($_ENV);

$app->setBasePath($envConfig->server['basePath'] ?? '');

// Primer MW
$app->addRoutingMiddleware();
// MW Out
$app->addMiddleware(new AddResponseHeaders);
$app->addErrorMiddleware(!$envConfig->server['isProd'], false, false)->setDefaultErrorHandler(ErrorHandler::class);
// MW In
$app->add(ReadUserFromToken::class);

// Routes
$app->group('', new Router($app));

$app->run();
