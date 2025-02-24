<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

use Kartalit\Schemas\TokenPayload;

interface TokenServiceInterface
{
    public function encodeToken(TokenPayload $payload, int $expirationTime): string;
    /**
     * Summary of decodeToken
     * @param string $token
     * @return TokenPayload
     * @throws \Kartalit\Errors\InvalidTokenException
     * @throws \Kartalit\Errors\ExpiredTokenException
     */
    public function decodeToken(string $token): TokenPayload;
    public function updateToken(string $token, int $expirationTime): string;
}
