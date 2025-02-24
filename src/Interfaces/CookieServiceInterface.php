<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface CookieServiceInterface
{
    public function getCookieValue(ServerRequestInterface $request, string $cookieName): string|null;
    public function setCookie(string $cookieValue, string $cookieName): void;
    public function deleteCookie(string $cookieName): void;
}
