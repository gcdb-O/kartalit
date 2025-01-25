<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Slim\Routing\RouteCollectorProxy;
use Kartalit\Controllers\AutorController;

class Router
{
    public function __invoke(RouteCollectorProxy $group)
    {
        $group->get("/", [AutorController::class, "get"]);
        $group->group("/calendari", CalendariRouter::class);
    }
}
