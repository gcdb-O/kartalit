<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Errors\UnauthorizedException;
use Kartalit\Models\Llegit;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\RenderContext;
use Kartalit\Services\Entity\LlegitService;
use Kartalit\Services\Entity\UsuariService;
use Kartalit\Interfaces\RenderServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class PerfilController extends WebController
{
    public function __construct(
        private RenderServiceInterface $renderService,
        private UsuariService $usuariService,
        private LlegitService $llegitService,
    ) {}
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        /** @var ?Usuari $reqUsuari */
        $reqUsuari = $request->getAttribute("usuari");
        $perfilUsuariId = isset($args["id"]) ? (int)$args["id"] : $reqUsuari?->getId();
        if (!$perfilUsuariId) {
            throw new UnauthorizedException();
        }
        /** @var Usuari $perfilUsuari */
        $perfilUsuari = $this->usuariService->getById($perfilUsuariId, true);
        $llegits = $this->llegitService->getByUsuari($perfilUsuari, $reqUsuari);
        $llegitsJson = array_map(fn(Llegit $llegit) => $llegit->toArray(), $llegits);
        $renderContextData = [
            "perfilUsuari" => $perfilUsuari->toArray($reqUsuari),
            "llegits" => $llegitsJson,
        ];
        $renderContext = new RenderContext($request, "Perfil", $renderContextData);
        return $this->renderService->render($response, "Pages/perfil.html.twig", $renderContext);
    }
}
