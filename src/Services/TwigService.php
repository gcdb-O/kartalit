<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Interfaces\RenderServiceInterface;
use Kartalit\Schemas\RenderContext;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

readonly class TwigService implements RenderServiceInterface
{
    public function __construct(private readonly Twig $twig) {}

    public function render(Response $res, string $template, RenderContext $context): Response
    {
        return $this->twig->render($res, $template, $context->getContext());
    }
}
