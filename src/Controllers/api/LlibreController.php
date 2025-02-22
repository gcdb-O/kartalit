<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Doctrine\Common\Collections\Collection;
use Kartalit\Enums\HttpStatusCode;
use Kartalit\Models\Llibre;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\LlibreService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LlibreController
{
    public function __construct(
        private ApiResponseService $apiResponseService,
        private LlibreService $llibreService,
    ) {}

    public function busca(Request $_, Response $res, array $args): Response
    {
        $searchToken = (string) $args["busca"];
        $llibres = $this->llibreService->search($searchToken, 20);
        $jsonData = array_map(fn(Llibre $llibre) => $llibre->getCobertesBasic(), $llibres);
        $apiRes = new ApiResponse($jsonData);
        return $this->apiResponseService->toJson($res, $apiRes, HttpStatusCode::OK);
    }
}
