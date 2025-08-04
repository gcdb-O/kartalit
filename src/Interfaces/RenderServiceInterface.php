<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

use Kartalit\Schemas\RenderContext;
use Psr\Http\Message\ResponseInterface as Response;

interface RenderServiceInterface
{
    public function render(Response $res, string $template, RenderContext $context): Response;
}
