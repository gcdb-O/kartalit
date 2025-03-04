<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\LlibreController;
use Kartalit\Middlewares\ValidatorMiddleware;
use Kartalit\Schemas\Validation\LlibreValidator;
use Slim\Routing\RouteCollectorProxy;

class LlibreRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/tots", [LlibreController::class, "getAll"])
            ->addMiddleware(new ValidatorMiddleware(
                LlibreValidator::class,
                "getAllQuery"
            ));
        $group->get("/{id:[0-9]+}", [LlibreController::class, "getById"]);
    }
}
