<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\AutorController;
use Slim\Routing\RouteCollectorProxy;

class AutorRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/all", [AutorController::class, "getAll"]);
        $group->get("/{id:[0-9]+}", [AutorController::class, "getById"]);
    }
}
