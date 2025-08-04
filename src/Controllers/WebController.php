<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Interfaces\RenderServiceInterface;
use Kartalit\Schemas\RenderContext;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class WebController
{
    public function __construct(private RenderServiceInterface $renderService) {}

    public function login(Request $req, Response $res): Response
    {
        return $this->renderService->render($res, "Pages/login.html.twig", new RenderContext($req, "Inicia sessió"));
    }
}
