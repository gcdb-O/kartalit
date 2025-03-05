<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\AuthController;
use Kartalit\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

class AuthRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->post("/login", [AuthController::class, "login"])->setName("login");
        $group->any("/logout", [AuthController::class, "logout"])
            ->add(AuthMiddleware::class);
    }
}
