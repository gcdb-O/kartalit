<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpStatusCode;
use Kartalit\Models\Llibre;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Schemas\PaginatedData;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\BibliotecaService;
use Kartalit\Services\LlibreService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BibliotecaController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private BibliotecaService $bibliotecaService,
        private LlibreService $llibreService,
    ) {}

    public function getAllByUser(Request $req, Response $res): Response
    {
        /** @var Usuari $usuari */
        $usuari = $req->getAttribute('usuari');
        $queryParams = $req->getQueryParams();
        $pagina = isset($queryParams['pagina']) ? (int) $queryParams['pagina'] : 1;

        $biblioteca = $this->llibreService->getBibliotecaByUsuari($usuari, $usuari, 20, $pagina - 1);
        $bibliotecaJson = PaginatedData::fromPagination($biblioteca, $pagina);

        $apiRes = new ApiResponse($bibliotecaJson, "Biblioteca de l'usuari");

        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
    public function postByLlibre(Request $req, Response $res, array $args): Response
    {
        $llibreId = (int) $args['llibreId'];
        /** @var Llibre $llibre */
        $llibre = $this->llibreService->getById($llibreId, true);
        /** @var ?Usuari $usuari */
        $usuari = $req->getAttribute('usuari');
        $nouBiblioteca = $this->bibliotecaService->create(
            llibre: $llibre,
            usuari: $usuari
        );
        $apiRes = new ApiResponse($nouBiblioteca->toArray(), "S'ha afegit el llibre a la teva Biblioteca");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::CREATED);
    }
}
