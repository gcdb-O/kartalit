<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Kartalit\Config\Config;
use function DI\create;

$container_bindings = [
    Config::class => create(Config::class)->constructor($_ENV),
    EntityManager::class => function (Config $config) {
        $doctConfig = ORMSetup::createAttributeMetadataConfiguration(paths: [__DIR__ . '/../src/Models'], isDevMode: true); // $isDevMode=true ja que sinó tinc problemes de Redis al deployment. 
        $doctConfig->setAutoGenerateProxyClasses(false);
        return new EntityManager(
            DriverManager::getConnection($config->db),
            $doctConfig
        );
    }
];

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions($container_bindings);

return $containerBuilder->build();
