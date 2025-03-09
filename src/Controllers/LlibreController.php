<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Autor;
use Kartalit\Models\Cita;
use Kartalit\Models\Idioma;
use Kartalit\Models\Llibre;
use Kartalit\Models\MapaLiterari;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\AutorService;
use Kartalit\Services\BibliotecaService;
use Kartalit\Services\IdiomaService;
use Kartalit\Services\LlibreService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class LlibreController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private AutorService $autorService,
        private IdiomaService $idiomaService,
        private LlibreService $llibreService,
        private BibliotecaService $bibliotecaService,
    ) {}
    public function getAll(Request $req, Response $res): Response
    {
        $queryParams = $req->getQueryParams();
        $pagina = isset($queryParams['pagina']) ? (int) $queryParams['pagina'] : 1;
        $limit = 24;
        $llibres = $this->llibreService->getAllPage($limit, $pagina - 1);
        $llibresTotal = count($llibres);
        $paginaTotal = ceil($llibresTotal / $limit);

        $twigContext = new TwigContext($req, "Tots els llibres", [
            "llibres" => $llibres->toArray("getCobertesBasic"),
            "llibresTotal" => $llibresTotal,
            "pagina" => $pagina,
            "paginaTotal" => $paginaTotal
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
        $twigContextData = ["llibre" => $llibre->toArray()];
        $mapaLiterari = $llibre->getMapaLiterari()->filter(function (MapaLiterari $mapa) use ($usuari) {
            return $mapa->getUsuari()->getId() === $usuari?->getId() || $mapa->getPrivat() === false;
        });
        $twigContextData["mapaLiterari"] = array_map(fn(MapaLiterari $ubicacio) => $ubicacio->toArray(), $mapaLiterari->toArray());
        $twigContextData["cites"] = array_map(fn(Cita $cita) => $cita->toArray(), $llibre->getCites()->toArray());
        if ($usuari !== null) {
            $twigContextData["biblioteca"] = $this->bibliotecaService->getBibliotecaFromLlibreUser($llibre, $usuari, $usuari)?->toArray();
        }
        $twigContext = new TwigContext($req, $llibre->getTitol(), $twigContextData);
        return $this->twigService->render(
            $res,
            "Pages/llibre.html.twig",
            $twigContext
        );
    }
    public function getNou(Request $req, Response $res): Response
    {
        $autors = $this->autorService->getAllOrdenat();
        $idiomes = $this->idiomaService->getAll();
        $autorsJson = array_map(fn(Autor $autor) => $autor->toArray(), $autors);
        $idiomesJson = array_map(fn(Idioma $idioma) => $idioma->toArray(), $idiomes);
        $twigContext = new TwigContext($req, "Afegir llibre", [
            "autors" => $autorsJson,
            "idiomes" => $idiomesJson
        ]);
        return $this->twigService->render($res, "Pages/llibreNou.html.twig", $twigContext);
    }
}
