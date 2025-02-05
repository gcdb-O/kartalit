<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

use Slim\Routing\RouteCollectorProxy;

interface RouterInterface
{
    public function __invoke(RouteCollectorProxy $group): void;
}
