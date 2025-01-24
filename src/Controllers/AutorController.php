<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Services\AutorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AutorController
{
    public function __construct(private AutorService $autorService) {}

    public function get(Request $_, Response $res): Response
    {
        $autor1 = $this->autorService->getById(2);
        $res->getBody()->write(json_encode($autor1, JSON_PRETTY_PRINT));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
