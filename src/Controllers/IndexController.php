<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Cita;
use Kartalit\Models\Llibre;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\CitaService;
use Kartalit\Services\LlibreService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController
{
    public function __construct(
        private TwigService $twigService,
        private CitaService $citaService,
        private LlibreService $llibreService,
    ) {}
    public function __invoke(Request $request, Response $response): Response
    {
        /** @var Cita $citaRandom */
        $citaRandom = $this->citaService->getRandom();
        $llibresNous = $this->llibreService->getNous();
        $twigContext = new TwigContext($request, data: [
            "citaRandom" => $citaRandom->getCitaObraArray(),
            "llibresNous" => array_map(fn(Llibre $llibre) => $llibre->getCobertesBasic(), $llibresNous)
        ]);
        return $this->twigService->render($response, "index.html.twig", $twigContext);
    }
}
