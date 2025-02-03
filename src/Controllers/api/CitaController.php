<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpResponseCode;
use Kartalit\Models\Autor;
use Kartalit\Models\Cita;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\CitaService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CitaController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private CitaService $citaService
    ) {}

    public function getRandom(Request $_, Response $res): Response
    {
        /** @var Cita $cita */
        $cita = $this->citaService->getRandom();
        $obra = $cita->getObra();
        $citaJson = [
            "cita" => $cita->getCita(),
            "obra" => [
                "id" => $obra?->getId(),
                "titolOriginal" => $obra?->getTitolOriginal(),
                "titolCatala" => $obra?->getTitolCatala(),
                "autors" => array_map(function (Autor $autor) {
                    return [
                        "nomComplet" => $autor->getNomComplet(),
                        "pseudonim" => $autor->getPseudonim(),
                    ];
                }, $obra->getAutors()->toArray()),
            ]
        ];
        $apiRes = new ApiResponse(data: $citaJson);
        return $this->apiResponseService->toJson($res, $apiRes, HttpResponseCode::OK);
    }
}
