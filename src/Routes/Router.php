<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Interfaces\RouterInterface;
use Kartalit\Middlewares\AddJsonResponseHeader;
use Slim\App;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

class Router implements RouterInterface
{
    public function __construct(protected App $app) {}
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->group("/api", new ApiRouter($this->app))
            // MW In
            ->addMiddleware(new BodyParsingMiddleware)
            // MW Out
            ->addMiddleware(new AddJsonResponseHeader);
        $group->group("", new WebRouter($this->app))
            // MW In
            ->addMiddleware(TwigMiddleware::create($this->app, $this->app->getContainer()->get(Twig::class)));
    }
}
