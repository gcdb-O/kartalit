<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\AutorController;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

class AutorRouter extends WebRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $authService = $this->app->getContainer()->get(AuthServiceInterface::class);
        $group->get("/{id:[0-9]+}", [AutorController::class, "getById"]);
        $group->get("/nou", [AutorController::class, "getNou"])
            ->addMiddleware(new AuthMiddleware($authService, 1));
    }
}
