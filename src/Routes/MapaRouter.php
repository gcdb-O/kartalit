<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\MapaController;
use Kartalit\Controllers\ObraController;
use Kartalit\Interfaces\RouterInterface;
use Slim\Routing\RouteCollectorProxy;

class MapaRouter implements RouterInterface
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("[/{userId:[0-9]+}]", [MapaController::class, "getByUsuari"]);
    }
}
