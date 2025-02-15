<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\Entity;
use Kartalit\Enums\HttpStatusCode;
use Kartalit\Errors\EntityNotFoundException;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\CitaService;
use Kartalit\Services\LlibreService;
use Kartalit\Services\ObraService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CitaController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private CitaService $citaService,
        private LlibreService $llibreService,
        private ObraService $obraService,
    ) {}

    public function getRandom(Request $_, Response $res): Response
    {
        $cita = $this->citaService->getRandom();
        $citaJson = $cita->getCitaObraArray();
        $apiRes = new ApiResponse(data: $citaJson);
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }

    public function postByLlibreObra(Request $req, Response $res, array $args): Response
    {
        $llibreId = (int) $args["llibreId"];
        $obraId = (int) $args["obraId"];
        /** @var ?Usuari $usuari */
        $usuari = $req->getAttribute("usuari");
        $llibre = $this->llibreService->getByIdWithCites($llibreId, $usuari->getId());
        if (!$llibre) {
            throw new EntityNotFoundException(Entity::LLIBRE, $llibreId);
        }
        $obra = $this->obraService->getById($obraId);
        if (!$obra) {
            throw new EntityNotFoundException(Entity::OBRA, $obraId);
        }
        $novaCitaData = $req->getParsedBody();
        $novaCita = $this->citaService->create(
            cita: $novaCitaData["cita"],
            pagina: (int) $novaCitaData["pagina"] ?? null,
            comentari: $novaCitaData["comentari"] ?? null, //TODO: Que sigui null i no pas string buit.
            usuari: $usuari,
            llibre: $llibre,
            obra: $obra,
            privat: (bool) $novaCitaData["privat"] ?? false
        );

        $apiRes = new ApiResponse($novaCita->getArray(), "Cita creada correctament");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::CREATED);
    }
}
