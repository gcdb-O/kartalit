<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

use Kartalit\Models\Usuari;

interface AuthServiceInterface
{
    public function createToken(Usuari $usuari, int $expirationTime): string;
    public function getUserFromToken(string $token): ?Usuari;
    public function setCookie(string $token): void;
}
