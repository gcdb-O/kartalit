<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Usuari;

class TwigContext
{
    public function __construct(
        private array $data = [],
        private ?Usuari $usuari = null,
        private ?string $titol = null,
    ) {}

    public function getContext(): array
    {
        $context = $this->data;
        $context["usuari"] = $this->usuari;
        $context["titol"] = ltrim($this->titol . " 📖 Kartalit");
        return $context;
    }
}
