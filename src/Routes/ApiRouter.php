<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Controllers\api\AuthController;
use Kartalit\Routes\api\AutorRouter;
use Kartalit\Routes\api\CalendariRouter;
use Kartalit\Routes\api\CitaRouter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class ApiRouter
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
        $group->post("/login", [AuthController::class, "login"]);
        $group->group("/autor", AutorRouter::class);
        $group->group("/cita", CitaRouter::class);
        $group->group("/calendari", CalendariRouter::class);
    }
}
