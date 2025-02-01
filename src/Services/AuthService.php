<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Config\Config;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Models\Usuari;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private Config $config,
        private JwtService $jwtService,
        private UsuariService $usuariService
    ) {}

    // #region Token
    public function createToken(Usuari $usuari): string
    {
        $payload = [
            "id" => $usuari->getId(),
            "usuari" => $usuari->getUsuari(),
            "nivell" => $usuari->getNivell(),
            "email" => $usuari->getEmail(),
        ];
        return $this->jwtService->jwtEncode($payload);
    }
    public function getUserFromToken(string $token): ?Usuari
    {
        try {
            $payload = $this->jwtService->jwtDecode($token);
            return $this->usuariService->getById($payload["id"]);
        } catch (\Throwable $th) {
            //TODO: Error propi
            throw $th;
        }
    }
    // #endregion
    // #region Cookie
    public function setCookie(string $token): void
    {
        setcookie(
            name: "token",
            value: $token,
            httponly: true,
            secure: false,
            domain: $this->config->server["domain"],
            path: $this->config->server["basePath"],
            expires_or_options: time() + (365 * 24 * 60 * 60),
        );
    }
    // #endregion

}
