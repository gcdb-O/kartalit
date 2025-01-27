<?php

declare(strict_types=1);

namespace Kartalit\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AddResponseHeaders
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        return $response
            ->withHeader("Content-Language", "ca")
            ->withHeader("Date", gmdate("D, d M Y H:i:s T"));
    }
}
