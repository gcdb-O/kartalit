<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Usuari;

class TwigContext
{
    public function __construct(
        private array $data = [],
        private ?string $titol = null,
        private ?Usuari $usuari = null,
    ) {}

    public function getContext(): array
    {
        $context = $this->data;
        $context["usuari"] = $this->usuari;
        $context["titol"] = ltrim($this->titol . " 📖 Kartalit");
        return $context;
    }
}
