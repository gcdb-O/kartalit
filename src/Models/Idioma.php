<?php

declare(strict_types=1);

namespace Kartalit\Models;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity]
#[Table(name: "idiomes")]
class Idioma
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'idiomaID')]
    private int $id;
    #[Column]
    private string $idioma;
    #[OneToMany(targetEntity: Obra::class, mappedBy: 'idioma')]
    private Collection $obres;

    public function __construct()
    {
        $this->obres = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdioma(): string
    {
        return $this->idioma;
    }

    public function setIdioma(string $idioma): void
    {
        $this->idioma = $idioma;
    }

    public function getObres(): Collection
    {
        return $this->obres;
    }

    public function addObra(Obra $obra): void
    {
        $obra->setIdiomaOriginal($this);
        $this->obres->add($obra);
    }
}
