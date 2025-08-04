<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Interfaces\RenderServiceInterface;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\RenderContext;
use Kartalit\Services\Entity\MapaService;
use Kartalit\Services\Entity\UsuariService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class MapaController extends WebController
{
    public function __construct(
        private RenderServiceInterface $renderService,
        private MapaService $mapaService,
        private UsuariService $usuariService,
    ) {
        parent::__construct($renderService);
    }
    public function getByUsuari(Request $req, Response $res, array $args): Response
    {
        /** @var ?Usuari $reqUser */
        $reqUser = $req->getAttribute('usuari');

        $userId = isset($args['userId']) ? (int) $args['userId'] : $reqUser?->getId();
        if (!$userId) {
            //TODO: De moment els no usuaris han de fer login
            return $this->login($req, $res);
        }
        /** @var Usuari $usuari */
        $usuari = $this->usuariService->getById($userId, true);
        // $mapaLiterari = $this->mapaService->getByUsuari($usuari, $reqUser);
        $renderContextData = ["usuariMapa" => $usuari->toArray()];

        return $this->renderService->render($res, "Pages/mapaLiterari.html.twig", new RenderContext($req, "Mapa Literari", $renderContextData));
    }
}
