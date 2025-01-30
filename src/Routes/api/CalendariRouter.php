<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\EsdevenimentController;
use Slim\Routing\RouteCollectorProxy;

class CalendariRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/avui", [EsdevenimentController::class, "getDiaMes"]);
    }
}
