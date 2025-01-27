<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Cita;
use Kartalit\Services\CitaService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CitaController
{
    public function __construct(private CitaService $citaService) {}

    public function getRandom(Request $_, Response $res): Response
    {
        /** @var Cita $cita */
        $cita = $this->citaService->getRandom();
        $citaJson = [
            "cita" => $cita->getCita(),
            "obra" => [
                "titolOriginal" => $cita->getObra()?->getTitolOriginal(),
                "titolCatala" => $cita->getObra()?->getTitolCatala(),
            ]
        ];
        $res->getBody()->write(json_encode($citaJson));
        return $res->withStatus(200);
    }
}
