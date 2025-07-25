<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Routes\api\AuthRouter;
use Kartalit\Routes\api\AutorRouter;
use Kartalit\Routes\api\BibliotecaRouter;
use Kartalit\Routes\api\CalendariRouter;
use Kartalit\Routes\api\CitaRouter;
use Kartalit\Routes\api\LlibreRouter;
use Kartalit\Routes\api\MapaRouter;
use Kartalit\Routes\api\ObraRouter;
use Kartalit\Routes\api\UbicacioRouter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class ApiRouter extends Router
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get("[/]", function (Request $_, Response $res): Response {
            $res->getBody()->write(json_encode([
                "API" => [
                    "nom" => "Kartalit",
                    "version" => "1.0.0",
                    "docs" => "In Progress..."
                ]
            ]));
            return $res->withStatus(200);
        });
        $group->group("/auth", AuthRouter::class);
        $group->group("/autor", new AutorRouter($this->app));
        $group->group("/biblioteca", BibliotecaRouter::class);
        $group->group("/calendari", CalendariRouter::class);
        $group->group("/cita", CitaRouter::class);
        $group->group("/llibre", LlibreRouter::class);
        $group->group("/mapa", MapaRouter::class);
        $group->group("/obra", new ObraRouter($this->app));
        $group->group("/ubicacio", UbicacioRouter::class);
    }
}
