<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpStatusCode;
use Kartalit\Models\MapaLiterari;
use Kartalit\Models\Obra;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\MapaService;
use Kartalit\Services\ObraService;
use Kartalit\Services\UsuariService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MapaController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private MapaService $mapaService,
        private ObraService $obraService,
        private UsuariService $usuariService
    ) {}

    public function getByUsuari(Request $req, Response $res, array $args): Response
    {
        /** @var ?Usuari $usuari */
        $reqUser = $req->getAttribute("usuari");
        $usuariId = (int) $args["userId"];
        /** @var Usuari $usuari */
        $usuari = $this->usuariService->getById($usuariId, true);

        $mapaLiterari = $this->mapaService->getByUsuari($usuari, $reqUser);
        $mapaLiterariJson = array_map(fn(MapaLiterari $mapa) => $mapa->toArray(true), $mapaLiterari);
        $apiRes = new ApiResponse($mapaLiterariJson, "Mapa literari de l'usuari amb id: $usuariId");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
    public function getByObra(Request $req, Response $res, array $args): Response
    {
        $obraId = (int) $args["obraId"];
        /** @var ?Usuari $usuari */
        $usuari = $req->getAttribute("usuari");
        $mapaLiterari = $this->mapaService->getByObra($obraId, $usuari?->getId() ?? null);
        $mapaLiterariJson = array_map(fn(MapaLiterari $mapa) => $mapa->toArray(), $mapaLiterari);
        $apiRes = new ApiResponse($mapaLiterariJson, "Mapa literari de l'obra amb id: $obraId");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
    public function postByObra(Request $req, Response $res, array $args): Response
    {
        $obraId = (int) $args["obraId"];
        /** @var ?Usuari $usuari */
        $usuari = $req->getAttribute("usuari");
        /** @var Obra $obra */
        $obra = $this->obraService->getById($obraId, true);
        $nouMapaData = $req->getParsedBody();
        $nouMapa = $this->mapaService->create(
            obra: $obra,
            usuari: $usuari,
            latitud: (float) $nouMapaData["latitud"],
            longitud: (float) $nouMapaData["longitud"],
            comentari: $nouMapaData["comentari"],
            adreca: isset($nouMapaData["adreca"]) ? (string) $nouMapaData["adreca"] : null,
            tipus: isset($nouMapaData["tipus"]) ? (string) $nouMapaData["tipus"] : null,
            precisio: (bool) $nouMapaData["precisio"],
            privat: isset($nouMapaData["privat"]) ? (bool) $nouMapaData["privat"] : false,
        );
        $apiRes = new ApiResponse($nouMapa->toArray(), "Ubicació creada correctament.");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::CREATED);
    }
}
