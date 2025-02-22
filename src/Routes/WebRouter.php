<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\IndexController;
use Kartalit\Controllers\PerfilController;
use Kartalit\Controllers\WebController;
use Kartalit\Middlewares\RedirectToMain;
use Slim\Routing\RouteCollectorProxy;

class WebRouter extends Router
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("[/]", IndexController::class);
        $group->get("/login", [WebController::class, "login"])
            ->addMiddleware(new RedirectToMain(true));
        //TODO: Separar el sense id amb MW auth?
        $group->get("/perfil[/{id:[0-9]*}]", PerfilController::class);
        $group->group("/llibre", LlibreRouter::class);
        $group->group("/mapa-literari", MapaRouter::class);
        $group->group("/obra", ObraRouter::class);
    }
}
