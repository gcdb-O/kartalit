<?php

declare(strict_types=1);

namespace Kartalit\Middlewares;

use Kartalit\Enums\Cookie;
use Kartalit\Interfaces\CookieServiceInterface;
use Kartalit\Interfaces\SessionServiceInterface;
use Kartalit\Interfaces\TokenServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private SessionServiceInterface $sessionService,
        private CookieServiceInterface $cookieService,
        private TokenServiceInterface $tokenService
    ) {}
    public function process(Request $request, RequestHandler $handler): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            $token = $this->cookieService->getCookieValue($request, Cookie::AUTH->value);
            if ($token !== null) {
                $this->sessionService->startSession();
                $newToken = $this->tokenService->updateToken($token, Cookie::AUTH->getDefaultExpTime());
                $this->cookieService->setCookie($newToken, Cookie::AUTH->value);
            }
        }
        return $handler->handle($request);
    }
}
