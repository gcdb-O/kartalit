<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Controllers\WebController;
use Kartalit\Enums\Entity;
use Kartalit\Errors\EntityNotFoundException;
use Kartalit\Models\Llibre;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\LlibreService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LlibreController extends WebController
{
    public function __construct(
        private TwigService $twigService,
        private LlibreService $llibreService
    ) {}
    public function getById(Request $req, Response $res, array $args): Response
    {
        $id = (int) $args["id"];
        /** @var ?Llibre $llibre */
        $llibre = $this->llibreService->getById($id);
        if (!$llibre) {
            throw new EntityNotFoundException(Entity::LLIBRE, $id);
        }
        $twigContext = new TwigContext($req, $llibre->getTitol(), [
            "llibre" => $llibre->getArray(),
        ]);
        return $this->twigService->render(
            $res,
            "llibre.html.twig",
            $twigContext
        );
    }
}
