<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use DateTime;
use Kartalit\Enums\HttpStatusCode;
use Kartalit\Helpers\DataValidation as DV;
use Kartalit\Models\Llibre;
use Kartalit\Models\Obra;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\Entity\BibliotecaService;
use Kartalit\Services\Entity\IdiomaService;
use Kartalit\Services\Entity\LlibreService;
use Kartalit\Services\Entity\ObraService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LlibreController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private BibliotecaService $bibliotecaService,
        private IdiomaService $idiomaService,
        private LlibreService $llibreService,
        private ObraService $obraService,
    ) {}

    public function busca(Request $_, Response $res, array $args): Response
    {
        $searchToken = (string) $args["busca"];
        $llibres = $this->llibreService->search($searchToken, 20);
        $jsonData = array_map(fn(Llibre $llibre) => $llibre->getCobertesBasic(), $llibres);
        $apiRes = new ApiResponse($jsonData);
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }

    public function post(Request $req, Response $res): Response
    {
        /** @var Usuari $usuari */
        $usuari = $req->getAttribute("usuari");

        $nouLlibreData = $req->getParsedBody();
        /** @var Obra $obra */
        $obra = is_numeric($nouLlibreData["obra"])
            ? $this->obraService->getById((int) $nouLlibreData["obra"], true)
            : $this->obraService->createFromObject($nouLlibreData["obra"]);

        $idiomaLlibre = $this->idiomaService->getById((int) $nouLlibreData["idioma"], true);
        $nouLlibre = $this->llibreService->create(
            titol: (string) $nouLlibreData["titol"],
            isbn: DV::issetAndNotEmptyString($nouLlibreData, "isbn") ? (string) $nouLlibreData["isbn"] : null,
            editorial: DV::issetAndNotEmptyString($nouLlibreData, "editorial") ? (string) $nouLlibreData["editorial"] : null,
            pagines: DV::issetAndNotEmptyString($nouLlibreData, "pagines") ? (int) $nouLlibreData["pagines"] : null,
            idioma: $idiomaLlibre,
            obra: $obra
        );

        $nouBiblioteca = $this->bibliotecaService->create(
            llibre: $nouLlibre,
            usuari: $usuari,
            condicio: (string) $nouLlibreData["condicio"],
            obtingut: DV::issetAndNotEmptyString($nouLlibreData, "obtingut") ? (string) $nouLlibreData["obtingut"] : null,
            motiu: DV::issetAndNotEmptyString($nouLlibreData, "motiu") ? (string) $nouLlibreData["motiu"] : null,
            preu: DV::issetAndNotEmptyString($nouLlibreData, "preu") ? (float) $nouLlibreData["preu"] : null,
            dataObtencio: DV::issetAndNotEmptyString($nouLlibreData, "data_obtencio") ? DateTime::createFromFormat("Y-m-d", $nouLlibreData["data_obtencio"]) : null,
        );

        $apiRes = new ApiResponse($nouBiblioteca->toArray(), "Llibre afegit correctament.");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::CREATED);
    }
}
