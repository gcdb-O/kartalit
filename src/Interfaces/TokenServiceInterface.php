<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

interface TokenServiceInterface
{
    public function encodeToken(array $payload, int $expirationTime): string;
    //TODO: Documentar els throws. Expiration, invalid
    public function decodeToken(string $token): array;
}
