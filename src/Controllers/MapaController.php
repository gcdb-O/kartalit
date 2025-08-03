<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Models\Usuari;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\Entity\MapaService;
use Kartalit\Services\Entity\UsuariService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class MapaController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private MapaService $mapaService,
        private UsuariService $usuariService,
    ) {
        parent::__construct($twigService);
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
        $twigContextData = ["usuariMapa" => $usuari->toArray()];

        return $this->twigService->render($res, "Pages/mapaLiterari.html.twig", new TwigContext($req, "Mapa Literari", $twigContextData));
    }
}
