<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Enums\Entity;
use Kartalit\Errors\EntityNotFoundException;
use Kartalit\Models\Llibre;
use Kartalit\Models\MapaLiterari;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\BibliotecaService;
use Kartalit\Services\LlibreService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class LlibreController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private LlibreService $llibreService,
        private BibliotecaService $bibliotecaService,
    ) {}
    public function getAll(Request $req, Response $res): Response
    {
        $llibres = $this->llibreService->getAll();
        $twigContext = new TwigContext($req, "Tots els llibres", [
            "llibres" => array_map(fn(Llibre $llibre) => $llibre->getCobertesBasic(), $llibres),
        ]);
        return $this->twigService->render($res, "Pages/llibres.html.twig", $twigContext);
    }
    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        /** @var ?Usuari $usuari */
        $usuari = $req->getAttribute("usuari");
        /** @var ?Llibre $llibre */
        $llibre = $this->llibreService->getById($id);
        if (!$llibre) {
            throw new EntityNotFoundException(Entity::LLIBRE, $id);
        }
        //TODO: Hauria d'incloure ja també en una sola crida les obres, cites, esdeveniments, mapa literari?
        $twigContextData = ["llibre" => $llibre->getArray()];
        $twigContextData["mapaLiterari"] = array_map(fn(MapaLiterari $mapaLiterari) => $mapaLiterari->getArray(), $llibre->getMapaLiterari()->toArray());
        if ($usuari !== null) {
            $twigContextData["biblioteca"] = $this->bibliotecaService->getBibliotecaFromLlibreUser($llibre, $usuari)?->getArray();
        }
        $twigContext = new TwigContext($req, $llibre->getTitol(), $twigContextData);
        return $this->twigService->render(
            $res,
            "Pages/llibre.html.twig",
            $twigContext
        );
    }
}
