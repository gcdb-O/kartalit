<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\LlibreController;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Middlewares\AuthMiddleware;
use Kartalit\Middlewares\ValidatorMiddleware;
use Kartalit\Schemas\Validation\PaginationValidator;
use Slim\Routing\RouteCollectorProxy;

class LlibreRouter extends WebRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $authService = $this->app->getContainer()->get(AuthServiceInterface::class);
        $group->get("s", [LlibreController::class, "listAll"])
            ->addMiddleware(new ValidatorMiddleware(PaginationValidator::class));
        $group->get("/tots", [LlibreController::class, "getAll"])
            ->addMiddleware(new ValidatorMiddleware(PaginationValidator::class));
        $group->get("/nou", [LlibreController::class, "getNou"])
            ->addMiddleware(new AuthMiddleware($authService, 1));
        $group->get("/{id:[0-9]+}", [LlibreController::class, "getById"]);
    }
}
