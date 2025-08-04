<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Helpers\DataValidation as DV;
use Kartalit\Interfaces\RenderServiceInterface;
use Kartalit\Schemas\RenderContext;
use Kartalit\Services\Entity\AutorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class AutorController extends WebController
{
    public function __construct(
        private RenderServiceInterface $renderService,
        private AutorService $autorService
    ) {}

    public function getAll(Request $req, Response $res): Response
    {
        $queryParams = $req->getQueryParams();
        $pagina = DV::issetAndNotEmptyString($queryParams, "pagina") ? (int) $queryParams['pagina'] : 1;
        $limit = 50;
        $autors = $this->autorService->getAllPaginated($limit, $pagina - 1);
        $autorsTotal = count($autors);
        $paginaTotal = ceil($autorsTotal / $limit);
        $renderContext = new RenderContext($req, "Tots els autors", [
            "autors" => $autors->toArray(),
            "autorsTotal" => $autorsTotal,
            "pagina" => $pagina,
            "paginaTotal" => $paginaTotal
        ]);
        return $this->renderService->render($res, "Pages/autors.html.twig", $renderContext);
    }
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
