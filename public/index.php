<?php

declare(strict_types=1);

use Kartalit\Errors\ErrorHandler;
use Kartalit\Middlewares\AddResponseHeaders;
use Kartalit\Middlewares\EncodeContent;
use Kartalit\Middlewares\ReadUserFromToken;
use Kartalit\Routes\Router;
use Slim\App;

/** @var App $app */
$app = require_once __DIR__ . '/../bootstrap.php';

$app->setBasePath($_ENV['ENV_SERVER_BASEPATH'] ?? '/');

// MW In
$app->addRoutingMiddleware();
$app->add(ReadUserFromToken::class);
// MW Out
// $app->add(new ErrorHandler);
$app->addMiddleware(new AddResponseHeaders);
$app->add(EncodeContent::class);
$errMw = $app->addErrorMiddleware(true, false, false);
$errMw->setDefaultErrorHandler(ErrorHandler::class);
$app->group('', new Router($app));

$app->run();
