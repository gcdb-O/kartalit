<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpStatusCode;
use Kartalit\Helpers\DataValidation as DV;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\AutorService;
use Kartalit\Services\IdiomaService;
use Kartalit\Services\ObraService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ObraController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private AutorService $autorService,
        private IdiomaService $idiomaService,
        private ObraService $obraService
    ) {}

    public function post(Request $req, Response $res): Response
    {
        $nouObraData = $req->getParsedBody();
        $autorObra = DV::issetAndNotEmptyString($nouObraData, "obra_autor") ? $this->autorService->getById((int) $nouObraData["obra_autor"]) : null;
        $idiomaObra = $this->idiomaService->getById((int) $nouObraData["idioma_original"], true);
        $nouObra = $this->obraService->create(
            titolOriginal: (string) $nouObraData["titol_original"],
            titolCatala: DV::issetAndNotEmptyString($nouObraData, "titol_catala") ? (string) $nouObraData["titol_catala"] : null,
            anyPublicacio: DV::issetAndNotEmptyString($nouObraData, "any_publicacio") ? (int) $nouObraData["any_publicacio"] : null,
            idiomaOriginal: $idiomaObra,
            autor: $autorObra
        );
        $apiRes = new ApiResponse($nouObra->toArray(), "Obra afegida correctament.");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::CREATED);
    }
}
