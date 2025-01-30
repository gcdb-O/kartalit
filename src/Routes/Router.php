<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Middlewares\AddJsonResponseHeader;
use Slim\App;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

class Router
{
    public function __construct(private App $app) {}
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->group("/api", ApiRouter::class)
            // MW In
            ->add(new BodyParsingMiddleware)
            // MW Out
            ->add(new AddJsonResponseHeader);
        $group->group("", WebRouter::class)
            // MW In
            ->add(TwigMiddleware::create($this->app, $this->app->getContainer()->get(Twig::class)));
    }
}
