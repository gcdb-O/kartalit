<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Models\Esdeveniment;
use Kartalit\Services\EsdevenimentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EsdevenimentController
{
    public function __construct(private EsdevenimentService $esdevenimentService) {}

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
        $res->getBody()->write(json_encode(["data" => $esdevenimentsJson]));
        return $res->withStatus(200);
    }
}
