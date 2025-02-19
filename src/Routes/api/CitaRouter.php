<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\CitaController;
use Kartalit\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

class CitaRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/random", [CitaController::class, "getRandom"]);
        $group->patch("/{id:[0-9]+}", [CitaController::class, "patchById"])
            ->add(AuthMiddleware::class);
        $group->post("/llibre/{llibreId:[0-9]+}/obra/{obraId:[0-9]+}", [CitaController::class, "postByLlibreObra"])
            ->add(AuthMiddleware::class);
    }
}
