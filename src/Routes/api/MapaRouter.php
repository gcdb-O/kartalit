<?php

declare(strict_types=1);

namespace Kartalit\Routes\api;

use Kartalit\Controllers\api\MapaController;
use Kartalit\Interfaces\RouterInterface;
use Kartalit\Middlewares\AuthMiddleware;
use Kartalit\Middlewares\ValidatorMiddleware;
use Kartalit\Schemas\Validation\MapaValidator;
use Slim\Routing\RouteCollectorProxy;

class MapaRouter implements RouterInterface
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("/obra/{obraId:[0-9]+}", [MapaController::class, "getByObra"]);
        $group->post("/obra/{obraId:[0-9]+}", [MapaController::class, "postByObra"])
            ->addMiddleware(new ValidatorMiddleware(MapaValidator::class, "post"))
            ->add(AuthMiddleware::class);
        $group->get("/usuari/{userId:[0-9]+}", [MapaController::class, "getByUsuari"]);
    }
}
