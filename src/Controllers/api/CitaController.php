<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpStatusCode;
use Kartalit\Errors\ForbiddenException;
use Kartalit\Helpers\DataValidation as DV;
use Kartalit\Models\Cita;
use Kartalit\Models\Obra;
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
        $llibre = $this->llibreService->getByIdWithCites($llibreId, $usuari->getId(), true);
        /** @var Obra $obra */
        $obra = $this->obraService->getById($obraId, true);
        $novaCitaData = $req->getParsedBody();
        $novaCita = $this->citaService->create(
            cita: (string) $novaCitaData["cita"],
            pagina: isset($novaCitaData["pagina"]) ? (int) $novaCitaData["pagina"] : null,
            comentari: DV::issetAndNotEmptyString($novaCitaData, "comentari") ? (string) $novaCitaData["comentari"] : null,
            usuari: $usuari,
            llibre: $llibre,
            obra: $obra,
            privat: isset($novaCitaData["privat"]) ? (bool) $novaCitaData["privat"] : false
        );
        $apiRes = new ApiResponse($novaCita->toArray(), "Cita creada correctament");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::CREATED);
    }

    public function patchById(Request $req, Response $res, array $args): Response
    {
        $citaId = (int) $args["id"];
        /** @var Cita $cita */
        $cita = $this->citaService->getById($citaId, true);
        /** @var Usuari $usuari */
        $usuari = $req->getAttribute("usuari");
        if ($cita->getUsuari()->getId() !== $usuari->getId()) {
            throw new ForbiddenException(message: "No pots editar una cita que no has creat tu.");
        }
        $citaData = $req->getParsedBody();

        if (isset($citaData["cita"])) {
            $cita->setCita($citaData["cita"]);
        }
        if (isset($citaData["comentari"])) {
            $cita->setComentari($citaData["comentari"]);
        }
        if (isset($citaData["pagina"])) {
            $cita->setPagina((int) $citaData["pagina"]);
        }
        if (isset($citaData["privat"])) {
            $cita->setPrivat((bool) $citaData["privat"]);
        }
        $apiJson = $cita->toArray();

        $this->citaService->persistAndFlush($cita);
        $apiRes = new ApiResponse($apiJson, "Cita editada correctament");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
    public function deleteById(Request $req, Response $res, array $args): Response
    {
        $citaId = (int) $args["id"];
        /** @var Cita $cita */
        $cita = $this->citaService->getById($citaId, true);
        /** @var Usuari $usuari */
        $usuari = $req->getAttribute("usuari");
        if ($cita->getUsuari()->getId() !== $usuari->getId()) {
            throw new ForbiddenException(message: "No pots eliminar una cita que no has creat tu.");
        }
        $citaJson = $cita->toArray();
        $this->citaService->deleteById($citaId);
        $apiRes = new ApiResponse($citaJson, "Cita elminada correctament");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
}
