<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Slim\App;

/** @var App $app */
$app = require_once __DIR__ . '\src\Config\bootstrap.php';
$container = $app->getContainer();

$entityManager = $container->get(EntityManager::class);

$commands = [];

ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands
);
