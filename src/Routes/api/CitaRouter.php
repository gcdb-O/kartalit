<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\CitaController;
use Slim\Routing\RouteCollectorProxy;

class CitaRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/random", [CitaController::class, "getRandom"]);
    }
}
