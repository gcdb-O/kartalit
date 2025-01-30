<?php

declare(strict_types=1);

namespace Kartalit\Controllers;

use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WebController
{
    public function __construct(private TwigService $twig) {}

    public function index(Request $_, Response $res): Response
    {
        return $this->twig->render($res, "index.html.twig", ["name" => "Kartalit"]);
    }
}
