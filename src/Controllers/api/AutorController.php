<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpStatusCode;
use Kartalit\Models\Autor;
use Kartalit\Models\Obra;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\AutorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AutorController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private AutorService $autorService
    ) {}

    public function getAll(Request $_, Response $res): Response
    {
        $autors = $this->autorService->getAll();
        $autorsJson = array_map(fn(Autor $autor) => $autor->toArray(), $autors);
        $apiRes = new ApiResponse($autorsJson, "Tots els autors.");
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
    public function getById(Request $_, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        /** @var Autor $autor */
        $autor = $this->autorService->getById($id, true);
        $autorJson = $autor->toArray();
        $autorJson["obres"] = array_map(fn(Obra $obra) => $obra->toArray(), $autor->getObres()->getValues());
        $apiRes = new ApiResponse(data: $autorJson);
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
}
