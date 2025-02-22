<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\LlibreController;
use Kartalit\Interfaces\RouterInterface;
use Slim\Routing\RouteCollectorProxy;

class LlibreRouter implements RouterInterface
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/busca/{busca}", [LlibreController::class, "busca"]);
    }
}
