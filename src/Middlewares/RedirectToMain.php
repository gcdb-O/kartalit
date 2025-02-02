<?php

declare(strict_types=1);

namespace Kartalit\Middlewares;

use Kartalit\Models\Usuari;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class RedirectToMain implements MiddlewareInterface
{
    // Generalment redirigeixo qui no està loguejat, però amb true redirigeixo qui sí està loguejat
    public function __construct(private bool $logged = false) {}

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $usuari = $request->getAttribute("usuari");
        if (($usuari instanceof Usuari) == $this->logged) {
            $response = new Response();
            return $response->withHeader("Location", "./")->withStatus(302);
        }
        return $handler->handle($request);
    }
}
