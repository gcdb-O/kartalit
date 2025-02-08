<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\MapaController;
use Slim\Routing\RouteCollectorProxy;

class MapaRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/obra/{obraId:[0-9]+}", [MapaController::class, "getByObra"]);
    }
}
