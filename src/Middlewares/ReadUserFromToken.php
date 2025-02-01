<?php

declare(strict_types=1);

namespace Kartalit\Middlewares;

use Kartalit\Interfaces\AuthServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ReadUserFromToken implements MiddlewareInterface
{
    public function __construct(private AuthServiceInterface $authService) {}
    public function process(Request $request, RequestHandler $handler): Response
    {
        $token = $request->getCookieParams()["token"] ?? null;
        if ($token !== null) {
            $userToken = $this->authService->getUserFromToken($token);
            $request = $request->withAttribute("usuari", $userToken);
        }
        return $handler->handle($request);
    }
}
