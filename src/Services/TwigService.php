<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Schemas\TwigContext;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

readonly class TwigService
{
    public function __construct(private readonly Twig $twig) {}

    public function render(Response $res, string $template, TwigContext $context): Response
    {
        return $this->twig->render($res, $template, $context->getContext());
    }
}
