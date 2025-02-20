<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\CitaController;
use Kartalit\Middlewares\AuthMiddleware;
use Kartalit\Middlewares\ValidatorMiddleware;
use Kartalit\Schemas\Validation\CitaValidator;
use Slim\Routing\RouteCollectorProxy;

class CitaRouter
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/random", [CitaController::class, "getRandom"]);
        $group->patch("/{id:[0-9]+}", [CitaController::class, "patchById"])
            ->addMiddleware(new ValidatorMiddleware(CitaValidator::class, "put"))
            ->add(AuthMiddleware::class);
        $group->post("/llibre/{llibreId:[0-9]+}/obra/{obraId:[0-9]+}", [CitaController::class, "postByLlibreObra"])
            ->addMiddleware(new ValidatorMiddleware(CitaValidator::class, "post"))
            ->add(AuthMiddleware::class);
    }
}
