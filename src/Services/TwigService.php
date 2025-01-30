<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class TwigService
{
    public function __construct(private readonly Twig $twig) {}

    // TODO: $data ha d'implementar una interfície que conté nom de pàgina, dades d'usuari provenint del token, i context normal.
    public function render(Response $res, string $template, array $data = []): Response
    {
        return $this->twig->render($res, $template, $data);
    }
}
