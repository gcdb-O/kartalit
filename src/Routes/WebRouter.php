<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\WebController;
use Slim\Routing\RouteCollectorProxy;

class WebRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("[/]", [WebController::class, "index"]);
    }
}
