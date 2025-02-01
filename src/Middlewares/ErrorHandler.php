<?php

declare(strict_types=1);

namespace Kartalit\Middlewares;

use Kartalit\Errors\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Throwable;

class ErrorHandler implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            switch (get_class($e)) {
                case NotFoundException::class:
                    /** @var NotFoundException $e */
                    return $this->handleNotFoundException($e);
                default:
                    // Se n'encarrega el error handler de slim
                    throw $e;
            }
        }
    }

    private function handleNotFoundException(NotFoundException $e): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode([
            "message" => $e->getMessage(),
            "exception" => [
                "type" => get_class($e),
                "code" => $e->getCode(),
            ]
        ]));
        return $response->withStatus($e->getCode());
    }
}
