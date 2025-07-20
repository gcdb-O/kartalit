<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\UbicacioController;
use Slim\Routing\RouteCollectorProxy;

class UbicacioRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $floatRegex = "[+-]?(?:[0-9]+(?:[.][0-9]*)?|[.][0-9]+)";
        $group->get("/{lat:$floatRegex}/{lon:$floatRegex}", [UbicacioController::class, "getOneByPoint"]);
    }
}
