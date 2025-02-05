<?php

declare(strict_types=1);

namespace Kartalit\Middlewares;

use Kartalit\Errors\ForbiddenException;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Schemas\TokenPayload;
use Kartalit\Services\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private AuthServiceInterface $authService, private ?int $nivellMax = 5) {}
    public function process(Request $request, RequestHandler $handler): Response
    {
        $token = (string) $request->getCookieParams()["token"];
        /** @var AuthService $this->authService */
        /** @var TokenPayload $payload */
        $payload = $this->authService->tokenService->decodeToken($token);
        if ($this->nivellMax !== null && $payload->nivell > $this->nivellMax) {
            throw new ForbiddenException();
        }

        return $handler->handle($request);
    }
}
