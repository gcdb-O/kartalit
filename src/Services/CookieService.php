<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Config\Config;
use Kartalit\Interfaces\CookieServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class CookieService implements CookieServiceInterface
{
    public function __construct(private Config $config) {}
    public function getCookieValue(Request $request, string $cookieName = "token"): string|null
    {
        return isset($request->getCookieParams()[$cookieName]) ? $request->getCookieParams()[$cookieName] : null;
    }
    public function setCookie(string $cookieValue, string $cookieName = "token"): void
    {
        //TODO: Controlar si hauria de posar la propietat SameSite
        setcookie(
            name: $cookieName,
            value: $cookieValue,
            httponly: true,
            secure: false,
            domain: $this->config->server["domain"],
            path: $this->config->server["basePath"],
            expires_or_options: time() + (365 * 24 * 60 * 60),
        );
    }
    public function deleteCookie(string $cookieName = "token"): void
    {
        setcookie(
            name: $cookieName,
            value: "",
            domain: $this->config->server["domain"],
            path: $this->config->server["basePath"],
            expires_or_options: time() - (60 * 60 * 24),
        );
        unset($_COOKIE[$cookieName]);
    }
}
