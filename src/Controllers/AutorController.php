<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Helpers\DataValidation as DV;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\Entity\AutorService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class AutorController extends WebController
{
    public function __construct(
        private TwigService $twigService,
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
        $twigContext = new TwigContext($req, "Tots els autors", [
            "autors" => $autors->toArray(),
            "autorsTotal" => $autorsTotal,
            "pagina" => $pagina,
            "paginaTotal" => $paginaTotal
        ]);
        return $this->twigService->render($res, "Pages/autors.html.twig", $twigContext);
    }
    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        $autor = $this->autorService->getById($id, true);
        $twigContextData = ["autor" => $autor->toArrayObresLlibres()];
        $twigContext = new TwigContext($req, $autor->getNomComplet(), $twigContextData);
        return $this->twigService->render($res, "Pages/autor.html.twig", $twigContext);
    }

    public function getNou(Request $req, Response $res): Response
    {
        $twigContext = new TwigContext($req, "Afegir autor");
        return $this->twigService->render($res, "Pages/autorNou.html.twig", $twigContext);
    }
}
