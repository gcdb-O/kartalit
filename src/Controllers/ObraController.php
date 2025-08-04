<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Autor;
use Kartalit\Models\Idioma;
use Kartalit\Models\Llibre;
use Kartalit\Models\Obra;
use Kartalit\Schemas\RenderContext;
use Kartalit\Services\Entity\AutorService;
use Kartalit\Services\Entity\IdiomaService;
use Kartalit\Services\Entity\ObraService;
use Kartalit\Interfaces\RenderServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class ObraController extends WebController
{
    public function __construct(
        private RenderServiceInterface $renderService,
        private AutorService $autorService,
        private IdiomaService $idiomaService,
        private ObraService $obraService,
    ) {}
    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        /** @var Obra $obra */
        $obra = $this->obraService->getById($id, true);
        $renderContextData = ["obra" => $obra->toArray()];
        $renderContextData["llibres"] = array_map(fn(Llibre $llibre) => $llibre->getCobertesBasic(), $obra->getLlibres()->toArray());
        $renderContext = new RenderContext($req, $obra->getTitolOriginal(), $renderContextData);
        return $this->renderService->render(
            $res,
            "Pages/obra.html.twig",
            $renderContext
        );
    }
    public function getNou(Request $req, Response $res): Response
    {
        $autors = $this->autorService->getAllOrdenat();
        $idiomes = $this->idiomaService->getAll();
        $autorsJson = array_map(fn(Autor $autor) => $autor->toArray(), $autors);
        $idiomesJson = array_map(fn(Idioma $idioma) => $idioma->toArray(), $idiomes);

        $renderContext = new RenderContext($req, "Afegir obra", [
            "autors" => $autorsJson,
            "idiomes" => $idiomesJson,
        ]);
        return $this->renderService->render($res, "Pages/obraNou.html.twig", $renderContext);
    }
}
