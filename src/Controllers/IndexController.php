<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Llibre;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\Entity\CitaService;
use Kartalit\Services\Entity\LlibreService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class IndexController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private CitaService $citaService,
        private LlibreService $llibreService,
    ) {}
    public function __invoke(Request $request, Response $response): Response
    {
        $citaRandom = $this->citaService->getRandom();
        $llibresNous = $this->llibreService->getNous();
        $twigContext = new TwigContext($request, data: [
            "citaRandom" => $citaRandom->getCitaObraArray(),
            "llibresNous" => array_map(fn(Llibre $llibre) => $llibre->getCobertesBasic(), $llibresNous)
        ]);
        return $this->twigService->render($response, "Pages/index.html.twig", $twigContext);
    }
}
