<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Slim\Routing\RouteCollectorProxy;
use Kartalit\Controllers\EsdevenimentController;

class CalendariRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/avui", [EsdevenimentController::class, "getDiaMes"]);
    }
}
