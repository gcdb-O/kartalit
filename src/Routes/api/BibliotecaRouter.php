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
        $group->get("[/]", [BibliotecaController::class, "getAllByUser"])
            ->add(AuthMiddleware::class);
        $group->post("/llibre/{llibreId:[0-9]+}", [BibliotecaController::class, "postByLlibre"])
            ->add(AuthMiddleware::class);
    }
}
