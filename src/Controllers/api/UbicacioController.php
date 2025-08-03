<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpStatusCode;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\Entity\UbicacioService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UbicacioController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private UbicacioService $ubicacioService,
    ) {}

    public function getOneByPoint(Request $_, Response $res, array $args): Response
    {
        $latitud = (float) $args["lat"];
        $longitud = (float) $args["lon"];
        $ubicacio = $this->ubicacioService->getSmallest($latitud, $longitud);

        $apiRes = new ApiResponse(["ubicacio" => $ubicacio->toArray()]);
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
}
