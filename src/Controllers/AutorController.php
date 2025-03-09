<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Schemas\TwigContext;
use Kartalit\Services\AutorService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class AutorController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private AutorService $autorService
    ) {}

    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        $autor = $this->autorService->getById($id, true);
        $twigContextData = ["autor" => $autor->toArrayObresLlibres()];
        $twigContext = new TwigContext($req, $autor->getNomComplet(), $twigContextData);
        return $this->twigService->render($res, "Pages/autor.html.twig", $twigContext);
    }
}
