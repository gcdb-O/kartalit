<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Schemas\RenderContext;
use Kartalit\Services\Entity\AutorService;
use Kartalit\Interfaces\RenderServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class AutorController extends WebController
{
    public function __construct(
        private RenderServiceInterface $renderService,
        private AutorService $autorService
    ) {}

    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        $autor = $this->autorService->getById($id, true);
        $renderContextData = ["autor" => $autor->toArrayObresLlibres()];
        $renderContext = new RenderContext($req, $autor->getNomComplet(), $renderContextData);
        return $this->renderService->render($res, "Pages/autor.html.twig", $renderContext);
    }

    public function getNou(Request $req, Response $res): Response
    {
        $renderContext = new RenderContext($req, "Afegir autor");
        return $this->renderService->render($res, "Pages/autorNou.html.twig", $renderContext);
    }
}
