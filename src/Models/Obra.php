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
use Doctrine\ORM\Mapping\JoinTable;

#[Entity]
#[Table(name: 'obres')]
class Obra
{
    #[Id]
    #[Column(name: 'obraID')]
    #[GeneratedValue]
    private int $id;

    #[Column(name: 'titol_original')]
    private ?string $titolOriginal;
    #[Column(name: 'titol_catala')]
    private ?string $titolCatala;
    #[Column(name: 'Any_publicacio')]
    private ?int $anyPublicacio;

    #[OneToMany(targetEntity: Esdeveniment::class, mappedBy: 'obra')]
    private Collection $esdeveniments;

    public function __construct()
    {
        $this->esdeveniments = new ArrayCollection();
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitolOriginal(): ?string
    {
        return $this->titolOriginal;
    }

    public function setTitolOriginal(?string $titolOriginal): void
    {
        $this->titolOriginal = $titolOriginal;
    }

    public function getTitolCatala(): ?string
    {
        return $this->titolCatala;
    }

    public function setTitolCatala(?string $titolCatala): void
    {
        $this->titolCatala = $titolCatala;
    }

    public function getAnyPublicacio(): ?int
    {
        return $this->anyPublicacio;
    }

    public function setAnyPublicacio(?int $anyPublicacio): void
    {
        $this->anyPublicacio = $anyPublicacio;
    }

    public function getEsdeveniments(): Collection
    {
        return $this->esdeveniments;
    }

    public function addEsdeveniment(Esdeveniment $esdeveniment): void
    {
        $esdeveniment->setObra($this);
        $this->esdeveniments->add($esdeveniment);
    }
}
