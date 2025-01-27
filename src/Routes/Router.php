<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class Router
{
    public function __invoke(RouteCollectorProxy $group)
    {
        $group->get("/", function (Request $_, Response $res): Response {
            $res->getBody()->write(json_encode([
                "API" => [
                    "nom" => "Kartalit",
                    "version" => "1.0.0",
                    "docs" => "In Progress..."
                ]
            ]));
            return $res->withStatus(200);
        });
        $group->group("/autor", AutorRouter::class);
        $group->group("/cita", CitaRouter::class);
        $group->group("/calendari", CalendariRouter::class);
    }
}
