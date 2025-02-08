<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Enums\Entity;
use Kartalit\Errors\EntityNotFoundException;
use Kartalit\Models\Obra;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\ObraService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class ObraController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private ObraService $obraService,
    ) {}
    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        // /** @var ?Usuari $usuari */
        // $usuari = $req->getAttribute("usuari");
        /** @var ?Obra $obra */
        $obra = $this->obraService->getById($id);
        if (!$obra) {
            throw new EntityNotFoundException(Entity::OBRA, $id);
        }
        $twigContextData = ["obra" => $obra->getArray()];
        $twigContext = new TwigContext($req, $obra->getTitolOriginal(), $twigContextData);
        return $this->twigService->render(
            $res,
            "Pages/obra.html.twig",
            $twigContext
        );
    }
}
