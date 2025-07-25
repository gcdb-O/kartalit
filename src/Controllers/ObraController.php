<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Autor;
use Kartalit\Models\Idioma;
use Kartalit\Models\Llibre;
use Kartalit\Models\Obra;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\AutorService;
use Kartalit\Services\IdiomaService;
use Kartalit\Services\ObraService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class ObraController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private AutorService $autorService,
        private IdiomaService $idiomaService,
        private ObraService $obraService,
    ) {}
    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        /** @var Obra $obra */
        $obra = $this->obraService->getById($id, true);
        $twigContextData = ["obra" => $obra->toArray()];
        $twigContextData["llibres"] = array_map(fn(Llibre $llibre) => $llibre->getCobertesBasic(), $obra->getLlibres()->toArray());
        $twigContext = new TwigContext($req, $obra->getTitolOriginal(), $twigContextData);
        return $this->twigService->render(
            $res,
            "Pages/obra.html.twig",
            $twigContext
        );
    }
    public function getNou(Request $req, Response $res): Response
    {
        $autors = $this->autorService->getAllOrdenat();
        $idiomes = $this->idiomaService->getAll();
        $autorsJson = array_map(fn(Autor $autor) => $autor->toArray(), $autors);
        $idiomesJson = array_map(fn(Idioma $idioma) => $idioma->toArray(), $idiomes);

        $twigContext = new TwigContext($req, "Afegir obra", [
            "autors" => $autorsJson,
            "idiomes" => $idiomesJson,
        ]);
        return $this->twigService->render($res, "Pages/obraNou.html.twig", $twigContext);
    }
}
