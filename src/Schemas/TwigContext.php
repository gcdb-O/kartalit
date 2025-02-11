<?php

declare(strict_types=1);

namespace Kartalit\Schemas;

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
    public function getTitol(): ?string
    {
        return $this->titol;
    }
    public function setTitol(?string $titol): void
    {
        $this->titol = $titol;
    }
    public function getData(): array
    {
        return $this->data;
    }
    public function setData(array $data): void
    {
        $this->data = $data;
    }
    public function getContext(): array
    {
        $titol = $this->titol === null ? "" : $this->titol . " 📖 ";
        $context = $this->data;
        $context["usuari"] = $this->usuari?->getArray($this->usuari);
        $context["titol"] = ltrim($titol . "Kartalit");
        return $context;
    }
}
