<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\LlibreController;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Middlewares\AuthMiddleware;
use Kartalit\Middlewares\ValidatorMiddleware;
use Kartalit\Routes\ApiRouter;
use Kartalit\Schemas\Validation\LlibreValidator;
use Slim\Routing\RouteCollectorProxy;

class LlibreRouter extends ApiRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $authService = $this->app->getContainer()->get(AuthServiceInterface::class);
        $group->post("[/]", [LlibreController::class, "post"])
            ->addMiddleware(new ValidatorMiddleware(LlibreValidator::class, "post"))
            ->addMiddleware(new AuthMiddleware($authService, 1));
        $group->get("/busca/{busca}", [LlibreController::class, "busca"]);
    }
}
