<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Helpers\DataValidation as DV;
use Kartalit\Interfaces\RenderServiceInterface;
use Kartalit\Models\Autor;
use Kartalit\Models\Cita;
use Kartalit\Models\Idioma;
use Kartalit\Models\MapaLiterari;
use Kartalit\Models\Obra;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\RenderContext;
use Kartalit\Services\Entity\AutorService;
use Kartalit\Services\Entity\BibliotecaService;
use Kartalit\Services\Entity\IdiomaService;
use Kartalit\Services\Entity\LlibreService;
use Kartalit\Services\Entity\ObraService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class LlibreController extends WebController
{
    public function __construct(
        private RenderServiceInterface $renderService,
        private AutorService $autorService,
        private ObraService $obraService,
        private IdiomaService $idiomaService,
        private LlibreService $llibreService,
        private BibliotecaService $bibliotecaService,
    ) {}
    public function getAll(Request $req, Response $res): Response
    {
        $queryParams = $req->getQueryParams();
        $pagina = DV::issetAndNotEmptyString($queryParams, "pagina") ? (int) $queryParams['pagina'] : 1;
        $limit = 24;
        $llibres = $this->llibreService->getAllPaginated($limit, $pagina - 1);
        $llibresTotal = count($llibres);
        $paginaTotal = ceil($llibresTotal / $limit);

        $renderContext = new RenderContext($req, "Tots els llibres", [
            "llibres" => $llibres->toArray("getCobertesBasic"),
            "llibresTotal" => $llibresTotal,
            "pagina" => $pagina,
            "paginaTotal" => $paginaTotal
        ]);
        return $this->renderService->render($res, "Pages/llibres.html.twig", $renderContext);
    }
    public function listAll(Request $req, Response $res): Response
    {
        $queryParams = $req->getQueryParams();
        $pagina = DV::issetAndNotEmptyString($queryParams, "pagina") ? (int) $queryParams['pagina'] : 1;
        $limit = 50;
        $llibres = $this->llibreService->getAllPaginated($limit, $pagina - 1);
        $llibresTotal = count($llibres);
        $paginaTotal = ceil($llibresTotal / $limit);

        $renderContext = new RenderContext($req, "Tots els llibres", [
            "llibres" => $llibres->toArray(),
            "llibresTotal" => $llibresTotal,
            "pagina" => $pagina,
            "paginaTotal" => $paginaTotal
        ]);
        return $this->renderService->render($res, "Pages/llibresLlista.html.twig", $renderContext);
    }
    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        /** @var ?Usuari $usuari */
        $usuari = $req->getAttribute("usuari");
        $llibre = $this->llibreService->getByIdWithCites($id, $usuari?->getId() ?? null, true);
        //TODO: Hauria d'incloure ja també en una sola crida les obres, cites, esdeveniments, mapa literari?
        $renderContextData = ["llibre" => $llibre->toArray()];
        $mapaLiterari = $llibre->getMapaLiterari()->filter(function (MapaLiterari $mapa) use ($usuari) {
            return $mapa->getUsuari()->getId() === $usuari?->getId() || $mapa->getPrivat() === false;
        });
        $renderContextData["mapaLiterari"] = array_map(fn(MapaLiterari $ubicacio) => $ubicacio->toArray(), $mapaLiterari->toArray());
        $renderContextData["cites"] = array_map(fn(Cita $cita) => $cita->toArray(), $llibre->getCites()->toArray());
        if ($usuari !== null) {
            $renderContextData["biblioteca"] = $this->bibliotecaService->getBibliotecaFromLlibreUser($llibre, $usuari, $usuari)?->toArray();
        }
        $renderContext = new RenderContext($req, $llibre->getTitol(), $renderContextData);
        return $this->renderService->render(
            $res,
            "Pages/llibre.html.twig",
            $renderContext
        );
    }
    public function getNou(Request $req, Response $res): Response
    {
        /** @var ?Usuari $usuari */
        $usuari = $req->getAttribute("usuari");

        $obres = $this->obraService->getAll();
        $autors = $this->autorService->getAllOrdenat();
        $idiomes = $this->idiomaService->getAll();
        $obresJson = array_map(fn(Obra $obra) => $obra->toArray(), $obres);
        $autorsJson = array_map(fn(Autor $autor) => $autor->toArray(), $autors);
        $idiomesJson = array_map(fn(Idioma $idioma) => $idioma->toArray(), $idiomes);
        $condicionsJson = ["Meu", "eBook", "Biblioteca", "Manga", "Altres"];
        if ($usuari !== null && $usuari->getId() === 1) array_push($condicionsJson, "Mama i Jaume");
        $renderContext = new RenderContext($req, "Afegir llibre", [
            "obres" => $obresJson,
            "autors" => $autorsJson,
            "idiomes" => $idiomesJson,
            "condicions" => $condicionsJson
        ]);
        return $this->renderService->render($res, "Pages/llibreNou.html.twig", $renderContext);
    }
}
