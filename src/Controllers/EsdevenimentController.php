<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Esdeveniment;
use Kartalit\Services\EsdevenimentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EsdevenimentController
{
    public function __construct(private EsdevenimentService $esdevenimentService) {}

    public function getDiaMes(Request $req, Response $res): Response
    {
        $esdeveniments = $this->esdevenimentService->getByDiaMes();
        $esdevenimentsJson = [];
        foreach ($esdeveniments as $esdeveniment) {
            $obra = $esdeveniment->getObra();
            $obraJson = $obra->getTitolOriginal();
            if ($obra->getTitolCatala() != null) {
                $obraJson .= " (" . $obra->getTitolCatala() . ")";
            }
            $esdevenimentJson = [
                "esdeveniment" => $esdeveniment->getExtra(),
                "obra" => $obraJson,
            ];
            array_push($esdevenimentsJson, $esdevenimentJson);
        }
        $res->getBody()->write(json_encode($esdevenimentsJson));
        return $res
            ->withHeader("Content-Type", "application/json")
            ->withStatus(200);
    }
}
