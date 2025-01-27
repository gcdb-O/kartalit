<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\CitaController;
use Slim\Routing\RouteCollectorProxy;

class CitaRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/random", [CitaController::class, "getRandom"]);
    }
}
