<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

use Kartalit\Models\Usuari;
use Psr\Http\Message\ServerRequestInterface;

interface AuthServiceInterface extends CookieServiceInterface
{
    public function createToken(Usuari $usuari, int $expirationTime): string;
    public function getUserFromToken(string $token): ?Usuari;
}
