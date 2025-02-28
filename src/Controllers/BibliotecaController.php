<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Usuari;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\LlibreService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class BibliotecaController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private LlibreService $llibreService,
    ) {}
    public function __invoke(Request $request, Response $response): Response
    {
        /** @var Usuari $usuari */
        $usuari = $request->getAttribute('usuari');
        $queryParams = $request->getQueryParams();
        $pagina = isset($queryParams['pagina']) ? (int) $queryParams['pagina'] : 1;
        $biblioteca = $this->llibreService->getBibliotecaByUsuari($usuari, $usuari, 20, $pagina - 1);

        $twigContext = new TwigContext($request, data: [
            "biblioteca" => $biblioteca->toArray(),
            "bibliotecaTotal" => count($biblioteca),
        ]);
        return $this->twigService->render($response, "Pages/biblioteca.html.twig", $twigContext);
    }
}
