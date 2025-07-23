<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\AutorController;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Middlewares\AuthMiddleware;
use Kartalit\Middlewares\ValidatorMiddleware;
use Kartalit\Routes\ApiRouter;
use Kartalit\Schemas\Validation\AutorValidator;
use Slim\Routing\RouteCollectorProxy;

class AutorRouter extends ApiRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $authService = $this->app->getContainer()->get(AuthServiceInterface::class);

        $group->get("/all", [AutorController::class, "getAll"]);
        $group->get("/{id:[0-9]+}", [AutorController::class, "getById"]);
        $group->post("[/]", [AutorController::class, "post"])
            ->addMiddleware(new ValidatorMiddleware(AutorValidator::class, "post"))
            ->addMiddleware(new AuthMiddleware($authService, 1));
    }
}
