<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\BibliotecaController;
use Kartalit\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

class BibliotecaRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->post("/llibre/{llibreId:[0-9]+}", [BibliotecaController::class, "postByLlibre"])
            ->add(AuthMiddleware::class);
    }
}
