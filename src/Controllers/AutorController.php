<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Obra;
use Kartalit\Services\AutorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AutorController
{
    public function __construct(private AutorService $autorService) {}

    public function get(Request $_, Response $res): Response
    {
        $autor1 = $this->autorService->getById(4);
        $autorJson = [
            "id" => $autor1->getId(),
            "nom" => $autor1->getNom(),
            "cognoms" => $autor1->getCognoms(),
            "pseudonim" => $autor1->getPseudonim(),
            "ordenador" => $autor1->getOrdenador(),
            "dataNaixement" => $autor1->getDataNaixement()?->format("Y-m-d"),
            "dataDefuncio" => $autor1->getDataDefuncio()?->format("Y-m-d"),
            "nacionalitat" => $autor1->getNacionalitat(),
            "notes" => $autor1->getNotes(),
            "obres" => array_map(function (Obra $obra) {
                return [
                    "id" => $obra->getId(),
                    "titolOriginal" => $obra->getTitolOriginal(),
                    "titolCatala" => $obra->getTitolCatala(),
                    "idiomaOriginal" => $obra->getIdiomaOriginal()?->getIdioma(),
                ];
            }, $autor1->getObres()->getValues()),

        ];
        $res->getBody()->write(json_encode($autorJson));
        return $res->withStatus(200);
    }
}
