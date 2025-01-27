<?php

declare(strict_types=1);

use Kartalit\Middlewares\AddJsonResponseHeader;
use Kartalit\Middlewares\AddResponseHeaders;
use Kartalit\Middlewares\ErrorHandler;
use Kartalit\Routes\Router;
use Middlewares\DeflateEncoder;
use Middlewares\GzipEncoder;
use Middlewares\TrailingSlash;
use Slim\App;
use Slim\Middleware\ContentLengthMiddleware;

/** @var App $app */
$app = require_once __DIR__ . '/../bootstrap.php';

$app->setBasePath($_ENV['ENV_SERVER_BASEPATH'] ?? '/');

// MW Out
$app->add(new ContentLengthMiddleware());
// $app->add(new ErrorHandler);
$app->add(new AddResponseHeaders);
$app->add(new GzipEncoder());
$app->add(new DeflateEncoder());
$app->add(new AddJsonResponseHeader);
// MW In
// $app->add(new TrailingSlash(true));
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errMw = $app->addErrorMiddleware(true, false, false);
$errorHandler = $errMw->getDefaultErrorHandler();
$errorHandler->forceContentType("application/json");
$app->group('', Router::class);

$app->run();
