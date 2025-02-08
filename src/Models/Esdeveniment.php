<?php

declare(strict_types=1);

namespace Kartalit\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'esdeveniments')]
class Esdeveniment
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: "esdevenimentID")]
    private int $id;
    #[Column(nullable: true)]
    private ?int $any;
    #[Column(nullable: true)]
    private ?int $mes;
    #[Column(nullable: true)]
    private ?int $dia;
    #[Column(type: "text")]
    private string $extra;
    #[ManyToOne(targetEntity: Obra::class, inversedBy: 'esdeveniments')]
    #[JoinColumn(name: 'obra', referencedColumnName: 'obraID')]
    private ?Obra $obra;
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getAny(): ?int
    {
        return $this->any;
    }
    public function setAny(?int $any): void
    {
        $this->any = $any;
    }
    public function getMes(): ?int
    {
        return $this->mes;
    }
    public function setMes(?int $mes): void
    {
        $this->mes = $mes;
    }
    public function getDia(): ?int
    {
        return $this->dia;
    }
    public function setDia(?int $dia): void
    {
        $this->dia = $dia;
    }
    public function getExtra(): string
    {
        return $this->extra;
    }
    public function setExtra(string $extra): void
    {
        $this->extra = $extra;
    }
    public function getObra(): ?Obra
    {
        return $this->obra;
    }
    public function setObra(?Obra $obra): void
    {
        $this->obra = $obra;
    }
}
