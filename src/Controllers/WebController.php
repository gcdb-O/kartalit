<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Schemas\TwigContext;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WebController
{
    public function __construct(protected TwigService $twig) {}

    public function login(Request $req, Response $res): Response
    {
        return $this->twig->render($res, "login.html.twig", new TwigContext($req, "Inicia sessió"));
    }
}
