<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Services\TwigContext;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WebController
{
    public function __construct(private TwigService $twig) {}

    public function index(Request $req, Response $res): Response
    {
        $context = new TwigContext($req, "", ["name" => "Kartalit"]);
        return $this->twig->render($res, "index.html.twig", $context);
    }
    public function login(Request $req, Response $res): Response
    {
        return $this->twig->render($res, "login.html.twig", new TwigContext($req, "Inicia sessió"));
    }
}
