<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpStatusCode;
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
        $cita = $this->citaService->getRandom();
        $citaJson = $cita->getCitaObraArray();
        $apiRes = new ApiResponse(data: $citaJson);
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
}
