<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Usuari;
use Psr\Http\Message\ServerRequestInterface as Request;

class TwigContext
{
    private ?Usuari $usuari;
    public function __construct(
        private Request $request,
        private ?string $titol = null,
        private ?array $data = [],
    ) {
        $this->usuari = $this->request->getAttribute("usuari");
    }
    public function getContext(): array
    {
        $context = $this->data;
        $context["usuari"] = $this->usuari?->getArray();
        $context["titol"] = ltrim($this->titol . " 📖 Kartalit");
        return $context;
    }
}
