<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\ObraController;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

class ObraRouter extends WebRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $authService = $this->app->getContainer()->get(AuthServiceInterface::class);

        $group->get("/{id:[0-9]+}", [ObraController::class, "getById"]);
        $group->get("/nou", [ObraController::class, "getNou"])
            ->addMiddleware(new AuthMiddleware($authService, 1));
    }
}
