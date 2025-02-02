<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Middlewares\AddJsonResponseHeader;
use Kartalit\Middlewares\ErrorHandler;
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
            ->addMiddleware(new BodyParsingMiddleware)
            // MW Out
            ->addMiddleware(new AddJsonResponseHeader);
        // ->addMiddleware(new ErrorHandler);
        $group->group("", WebRouter::class)
            // MW In
            ->addMiddleware(TwigMiddleware::create($this->app, $this->app->getContainer()->get(Twig::class)));
    }
}
