<?php

declare(strict_types=1);

namespace Kartalit\Schemas;

use Kartalit\Models\Usuari;
use stdClass;

class TokenPayload
{
    public function __construct(
        public int $id,
        public string $usuari,
        public int $nivell,
        public string $email
    ) {}
    public static function createFromUsuari(Usuari $usuari): self
    {
        return new self(
            id: $usuari->getId(),
            usuari: $usuari->getUsuari(),
            nivell: $usuari->getNivell(),
            email: $usuari->getEmail(),
        );
    }
    public static function readFromObject(stdClass $tokenData): self
    {
        return new self(
            id: $tokenData->id,
            usuari: $tokenData->usuari,
            nivell: $tokenData->nivell,
            email: $tokenData->email,
        );
    }
    public function getArray(): array
    {
        return [
            "id" => $this->id,
            "usuari" => $this->usuari,
            "nivell" => $this->nivell,
            "email" => $this->email,
        ];
    }
}
