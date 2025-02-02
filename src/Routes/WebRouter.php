<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\WebController;
use Kartalit\Middlewares\RedirectToMain;
use Slim\Routing\RouteCollectorProxy;

class WebRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("[/]", [WebController::class, "index"]);
        $group->get("/login", [WebController::class, "login"])
            ->addMiddleware(new RedirectToMain(true));
    }
}
