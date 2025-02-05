<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\AuthController;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Middlewares\AuthMiddleware;
use Kartalit\Routes\Router;
use Slim\Routing\RouteCollectorProxy;

class AuthRouter extends Router
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $container = $this->app->getContainer();
        /** @var AuthServiceInterface $authService */
        $authService = $container->get(AuthServiceInterface::class);

        $group->post("/login", [AuthController::class, "login"])->setName("login");
        //TODO: Protegir ruta logout?
        $group->any("/logout", [AuthController::class, "logout"])
            ->add(new AuthMiddleware($authService));
    }
}
