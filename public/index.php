<?php

declare(strict_types=1);

use Kartalit\Controllers\AutorController;
use Kartalit\Controllers\EsdevenimentController;

$app = require_once __DIR__ . '/../bootstrap.php';

$app->setBasePath($_ENV['ENV_SERVER_BASEPATH'] ?? '/');

$app->get('/', [AutorController::class, 'get']);
$app->get('/avui', [EsdevenimentController::class, 'getDiaMes']);

$app->run();
