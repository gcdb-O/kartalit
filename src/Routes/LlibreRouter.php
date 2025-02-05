<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\LlibreController;
use Slim\Routing\RouteCollectorProxy;

class LlibreRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/{id:[0-9]+}", [LlibreController::class, "getById"]);
    }
}
