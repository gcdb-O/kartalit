<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Kartalit\Config\Config;
use Kartalit\Helpers\RandomSort;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Interfaces\TokenServiceInterface;
use Kartalit\Services\AuthService;
use Kartalit\Services\JwtService;
use Slim\Views\Twig;

use function DI\create;
use function DI\get;

$envConfig = new Config($_ENV);
$container_bindings = [
    Config::class => create(Config::class)->constructor($_ENV),
    EntityManager::class => function (Config $config) {
        $doctConfig = ORMSetup::createAttributeMetadataConfiguration(paths: [__DIR__ . '/../src/Models'], isDevMode: true); // $isDevMode=true ja que sinó tinc problemes de Redis al deployment.
        $doctConfig->setAutoGenerateProxyClasses(true);
        $doctConfig->addCustomNumericFunction('RANDOMSORT', RandomSort::class);
        return new EntityManager(
            DriverManager::getConnection($config->db),
            $doctConfig
        );
    },
    Twig::class => function (Config $config) {
        $cacheRoute = $config->server['isProd'] === true ? __DIR__ . '/../../var/cache/twig' : false;
        $twig = Twig::create(__DIR__ . "/../Templates", ["cache" => $cacheRoute]);
        $twig->getEnvironment()->addGlobal("basePath", $config->server["basePath"]);
        return $twig;
    },
    AuthServiceInterface::class => get(AuthService::class),
    TokenServiceInterface::class => get(JwtService::class),
];

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions($container_bindings);
if ($envConfig->server['isProd'] === true) {
    $containerBuilder->enableCompilation(__DIR__ . '/../../var/cache');
}

return $containerBuilder->build();
