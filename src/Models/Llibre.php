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
#[Table(name: "llibres")]
class Llibre
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: "llibreID")]
    private int $id;
    #[Column]
    private string $titol;
    #[Column(type: "blob", nullable: true)]
    private $coberta;
    #[Column]
    private ?string $isbn;
    #[Column]
    private ?string $editorial;
    #[Column(type: "smallint")]
    private ?int $pagines;
    #[ManyToOne(targetEntity: Idioma::class, inversedBy: "llibres")]
    #[JoinColumn(name: "idioma", referencedColumnName: "idiomaID")]
    private Idioma $idioma;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitol(): string
    {
        return $this->titol;
    }

    public function setTitol(string $titol): void
    {
        $this->titol = $titol;
    }

    public function getCoberta()
    {
        return $this->coberta;
    }

    public function setCoberta($coberta): void
    {
        $this->coberta = $coberta;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getEditorial(): ?string
    {
        return $this->editorial;
    }

    public function setEditorial(?string $editorial): void
    {
        $this->editorial = $editorial;
    }

    public function getPagines(): ?int
    {
        return $this->pagines;
    }

    public function setPagines(?int $pagines): void
    {
        $this->pagines = $pagines;
    }

    public function getIdioma(): Idioma
    {
        return $this->idioma;
    }

    public function setIdioma(Idioma $idioma): void
    {
        $this->idioma = $idioma;
    }
}
