<?php

declare(strict_types=1);

use Kartalit\Middlewares\AddResponseHeaders;
use Kartalit\Middlewares\EncodeContent;
use Kartalit\Middlewares\ErrorHandler;
use Kartalit\Routes\Router;
use Slim\App;

/** @var App $app */
$app = require_once __DIR__ . '/../bootstrap.php';

$app->setBasePath($_ENV['ENV_SERVER_BASEPATH'] ?? '/');

// MW In
$app->addBodyParsingMiddleware();
// MW Out
// $app->add(new ErrorHandler);
$app->add(new AddResponseHeaders);
$app->add(EncodeContent::class);
$errMw = $app->addErrorMiddleware(true, false, false);
$errorHandler = $errMw->getDefaultErrorHandler();
$errorHandler->forceContentType("application/json");
$app->group('', Router::class);

$app->run();
