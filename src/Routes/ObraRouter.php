<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\ObraController;
use Slim\Routing\RouteCollectorProxy;

class ObraRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        // $group->get("/tots", [LlibreController::class, "getAll"]);
        $group->get("/{id:[0-9]+}", [ObraController::class, "getById"]);
    }
}
