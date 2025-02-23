<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Cita;
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
        $llibre = $this->llibreService->getByIdWithCites($id, $usuari?->getId() ?? null, true);
        //TODO: Hauria d'incloure ja també en una sola crida les obres, cites, esdeveniments, mapa literari?
        $twigContextData = ["llibre" => $llibre->getArray()];
        $mapaLiterari = $llibre->getMapaLiterari()->filter(function (MapaLiterari $mapa) use ($usuari) {
            return $mapa->getUsuari()->getId() === $usuari?->getId() || $mapa->getPrivat() === false;
        });
        $twigContextData["mapaLiterari"] = array_map(fn(MapaLiterari $ubicacio) => $ubicacio->getArray(), $mapaLiterari->toArray());
        $twigContextData["cites"] = array_map(fn(Cita $cita) => $cita->getArray(), $llibre->getCites()->toArray());
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
