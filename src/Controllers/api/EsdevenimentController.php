<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use DateTime;
use Kartalit\Enums\ApiResponseStatus;
use Kartalit\Enums\HttpStatusCode;
use Kartalit\Models\Esdeveniment;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\Entity\EsdevenimentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EsdevenimentController
{
    public function __construct(
        protected ApiResponseService $apiResponseService,
        private EsdevenimentService $esdevenimentService
    ) {}

    public function getDiaMes(Request $_, Response $res): Response
    {
        $esdeveniments = $this->esdevenimentService->getByDiaMes();
        $esdevenimentsJson = [];
        /** @var Esdeveniment $esdeveniment */
        foreach ($esdeveniments as $esdeveniment) {
            $obra = $esdeveniment->getObra();
            $obraJson = [
                "id" => $obra->getId(),
                "titolOriginal" => $obra->getTitolOriginal(),
                "titolCatala" => $obra->getTitolCatala(),
            ];
            $esdevenimentJson = [
                "esdeveniment" => $esdeveniment->getExtra(),
                "any" => $esdeveniment->getAny(),
                "mes" => $esdeveniment->getMes(),
                "dia" => $esdeveniment->getDia(),
                "obra" => $obraJson,
            ];
            array_push($esdevenimentsJson, $esdevenimentJson);
        }
        $apiResponse = new ApiResponse($esdevenimentsJson);
        if (count($esdevenimentsJson) == 0) {
            $apiResponse->setMessage("No s'ha trobat cap esdeveniment per avui");
            $apiResponse->setStatus(ApiResponseStatus::NOT_FOUND);
        }
        $expires = (new DateTime())->setTime(23, 59, 59);
        $res = $res->withHeader("Cache-Control", "public, max-age=86400");
        $res = $res->withHeader("Expires", $expires->format(DateTime::RFC1123));
        return $this->apiResponseService->toJson($res, $apiResponse, HttpStatusCode::OK);
    }
}
