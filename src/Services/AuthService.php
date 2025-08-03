<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Config\Config;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Interfaces\TokenServiceInterface;
use Kartalit\Models\Usuari;
use Kartalit\Schemas\TokenPayload;
use Kartalit\Services\Entity\UsuariService;

readonly class AuthService extends CookieService implements AuthServiceInterface
{
    public function __construct(
        private Config $config,
        private UsuariService $usuariService,
        public TokenServiceInterface $tokenService,
    ) {
        parent::__construct($config);
    }

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
}
