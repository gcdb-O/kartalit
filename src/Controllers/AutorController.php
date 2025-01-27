<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Errors\NotFoundException;
use Kartalit\Models\Autor;
use Kartalit\Models\Obra;
use Kartalit\Services\AutorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AutorController
{
    public function __construct(private AutorService $autorService) {}

    public function getAll(Request $_, Response $res): Response
    {
        $autors = $this->autorService->getAll();
        $autorsJson = array_map(fn(Autor $autor) => $autor->getArray(), $autors);
        $res->getBody()->write(json_encode($autorsJson));
        return $res->withStatus(200);
    }
    public function getById(Request $_, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        $autor = $this->autorService->getById($id);
        if (!$autor) {
            throw new NotFoundException((string)$id);
        }
        $autorJson = $autor->getArray();
        $autorJson["obres"] = array_map(fn(Obra $obra) => $obra->getArray(), $autor->getObres()->getValues());
        $res->getBody()->write(json_encode($autorJson));
        return $res->withStatus(200);
    }
}
