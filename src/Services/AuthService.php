<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Config\Config;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Interfaces\TokenServiceInterface;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\TokenPayload;

readonly class AuthService implements AuthServiceInterface
{
    public function __construct(
        private Config $config,
        public TokenServiceInterface $tokenService,
        private UsuariService $usuariService
    ) {}

    // #region Token
    public function createToken(Usuari $usuari, int $expirationTime = 3600): string
    {
        $payload = TokenPayload::createFromUsuari($usuari);
        return $this->tokenService->encodeToken($payload, $expirationTime);
    }
    public function getUserFromToken(string $token): ?Usuari
    {
        try {
            $payload = $this->tokenService->decodeToken($token);
            return $this->usuariService->getById($payload->id);
        } catch (\Throwable $th) {
            // El MW d'autentificació ja s'encarregarà de gestionar errors de token.
            return null;
        }
    }
    // #endregion
    // #region Cookie
    public function setCookie(string $value, string $name = "token"): void
    {
        //TODO: Controlar si hauria de posar la propietat SameSite
        setcookie(
            name: $name,
            value: $value,
            httponly: true,
            secure: false,
            domain: $this->config->server["domain"],
            path: $this->config->server["basePath"],
            expires_or_options: time() + (365 * 24 * 60 * 60),
        );
    }
    public function deleteCookie(string $name = "token"): void
    {
        setcookie(
            name: $name,
            value: "",
            domain: $this->config->server["domain"],
            path: $this->config->server["basePath"],
            expires_or_options: time() - (60 * 60 * 24),
        );
        unset($_COOKIE[$name]);
    }
    // #endregion

}
