<?php

declare(strict_types=1);

namespace Kartalit\Routes;

use Kartalit\Config\Config;
use Kartalit\Controllers\BibliotecaController;
use Kartalit\Controllers\IndexController;
use Kartalit\Controllers\PerfilController;
use Kartalit\Controllers\WebController;
use Kartalit\Middlewares\AuthMiddleware;
use Kartalit\Middlewares\RedirectToMain;
use Kartalit\Middlewares\ValidatorMiddleware;
use Kartalit\Schemas\Validation\BibliotecaValidator;
use Slim\Routing\RouteCollectorProxy;

class WebRouter extends Router
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $config = $this->app->getContainer()->get(Config::class);

        $group->get("[/]", IndexController::class);
        $group->group("/autor", new AutorRouter($this->app));
        $group->get("/biblioteca", BibliotecaController::class)
            ->addMiddleware(new ValidatorMiddleware(BibliotecaValidator::class))
            ->add(AuthMiddleware::class);
        $group->get("/login", [WebController::class, "login"])
            ->addMiddleware(new RedirectToMain($config, true));
        $group->get("/perfil/[{id:[0-9]+}]", PerfilController::class);
        $group->get("/perfil", PerfilController::class)
            ->add(AuthMiddleware::class);
        $group->group("/llibre", new LlibreRouter($this->app));
        $group->group("/mapa-literari", MapaRouter::class);
        $group->group("/obra", new ObraRouter($this->app));
    }
}
