<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;


$app = require __DIR__ . '/bootstrap.php';
$container = $app->getContainer();

$entityManager = $container->get(EntityManager::class);

$commands = [];

ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands
);
