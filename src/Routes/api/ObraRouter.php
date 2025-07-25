<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\ObraController;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Middlewares\AuthMiddleware;
use Kartalit\Middlewares\ValidatorMiddleware;
use Kartalit\Routes\ApiRouter;
use Kartalit\Schemas\Validation\ObraValidator;
use Slim\Routing\RouteCollectorProxy;

class ObraRouter extends ApiRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $authService = $this->app->getContainer()->get(AuthServiceInterface::class);

        $group->post("[/]", [ObraController::class, "post"])
            ->addMiddleware(new ValidatorMiddleware(ObraValidator::class, "post"))
            ->addMiddleware(new AuthMiddleware($authService, 1));
    }
}
