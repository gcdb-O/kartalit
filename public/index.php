<?php

declare(strict_types=1);

use Kartalit\Config\Config;
use Kartalit\Errors\ErrorHandler;
use Kartalit\Middlewares\AddResponseHeaders;
use Kartalit\Middlewares\EncodeContent;
use Kartalit\Middlewares\ReadUserFromToken;
use Kartalit\Routes\Router;
use Slim\App;


/** @var App $app */
$app = require_once __DIR__ . '/../bootstrap.php';

$envConfig = new Config($_ENV);

$app->setBasePath($envConfig->server['basePath'] ?? '/');

// MW In
$app->addRoutingMiddleware();
$app->add(ReadUserFromToken::class);
// MW Out
// $app->add(new ErrorHandler);
$app->addMiddleware(new AddResponseHeaders);
$app->add(EncodeContent::class);
$errMw = $app->addErrorMiddleware(!$envConfig->server['isProd'], false, false);
$errMw->setDefaultErrorHandler(ErrorHandler::class);
$app->group('', new Router($app));

$app->run();
