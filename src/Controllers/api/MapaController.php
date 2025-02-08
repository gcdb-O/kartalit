<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpStatusCode;
use Kartalit\Models\MapaLiterari;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\MapaService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MapaController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private MapaService $mapaService,
    ) {}

    public function getByObra(Request $req, Response $res, array $args): Response
    {
        $obraId = (int) $args["obraId"];
        /** @var ?Usuari $usuari */
        $usuari = $req->getAttribute("usuari");
        $mapaLiterari = $this->mapaService->getByObra($obraId, $usuari?->getId() ?? null);
        $mapaLiterariJson = array_map(fn(MapaLiterari $mapa) => $mapa->getArray(), $mapaLiterari);
        $apiRes = new ApiResponse($mapaLiterariJson, "Mapa literari de l'obra amb id: $obraId");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
}
