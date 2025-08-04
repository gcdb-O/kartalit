<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Schemas\RenderContext;
use Kartalit\Interfaces\RenderServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class WebController
{
    public function __construct(protected RenderServiceInterface $twig) {}

    public function login(Request $req, Response $res): Response
    {
        return $this->twig->render($res, "Pages/login.html.twig", new RenderContext($req, "Inicia sessió"));
    }
}
