<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Kartalit\Config\Config;
use Kartalit\Helpers\RandomSort;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Interfaces\CookieServiceInterface;
use Kartalit\Interfaces\SessionServiceInterface;
use Kartalit\Interfaces\TokenServiceInterface;
use Kartalit\Services\AuthService;
use Kartalit\Services\CookieService;
use Kartalit\Services\JwtService;
use Kartalit\Services\SessionService;
use LongitudeOne\Spatial\DBAL\Types\Geometry\MultiPolygonType;
use LongitudeOne\Spatial\DBAL\Types\Geometry\PointType;
use LongitudeOne\Spatial\ORM\Query\AST\Functions\Standard\StAsText;
use LongitudeOne\Spatial\ORM\Query\AST\Functions\Standard\StContains;
use Slim\Views\Twig;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use function DI\create;
use function DI\get;

$envConfig = new Config($_ENV);
$container_bindings = [
    Config::class => create(Config::class)->constructor($_ENV),
    EntityManager::class => function (Config $config) {
        $isProd = (bool) $config->server['isProd'];

        $doctrineConfig = ORMSetup::createAttributeMetadataConfiguration(paths: [__DIR__ . '/../src/Models'], isDevMode: true); // $isDevMode=true ja que sinó tinc problemes de Redis al deployment.

        $doctrineConfig->setProxyDir(__DIR__ . '/../../var/tmp/doctrine/proxies');
        $doctrineConfig->setProxyNamespace('DoctrineProxies');
        //TODO: Ideal en producció seria false però hauria de trobar la manera de generar les proxyclasses
        $doctrineConfig->setAutoGenerateProxyClasses(true);

        $queryCache = $isProd ? new PhpFilesAdapter(namespace: "doctrine_queries", directory: __DIR__ . '/../../var/cache/doctrine') : new ArrayAdapter();
        $metadataCache = $isProd ? new PhpFilesAdapter(namespace: "doctrine_metadata", directory: __DIR__ . '/../../var/cache/doctrine') : new ArrayAdapter();
        $doctrineConfig->setQueryCache($queryCache);
        $doctrineConfig->setMetadataCache($metadataCache);
        //TODO: Pensar si resultCache, però com ho aplico en funció de isProd als serveis sense massa complicació...

        Type::addType('point', PointType::class);
        Type::addType('multipolygon', MultiPolygonType::class);

        $doctrineConfig->addCustomNumericFunction('RANDOMSORT', RandomSort::class);
        $doctrineConfig->addCustomNumericFunction('ST_Contains', StContains::class);
        $doctrineConfig->addCustomStringFunction('ST_AsText', StAsText::class);
        return new EntityManager(
            DriverManager::getConnection($config->db),
            $doctrineConfig
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
    CookieServiceInterface::class => get(CookieService::class),
    SessionServiceInterface::class => get(SessionService::class),
];

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions($container_bindings);
if ($envConfig->server['isProd'] === true) {
    $containerBuilder->enableCompilation(__DIR__ . '/../../var/cache');
}

return $containerBuilder->build();
