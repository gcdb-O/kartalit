<?php

declare(strict_types=1);

namespace Kartalit\Models;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity]
#[Table(name: 'autors')]
class Autor
{
    #[Id]
    #[Column(name: 'autorID')]
    #[GeneratedValue]
    private int $id;
    #[Column()]
    private string $nom;
    #[Column()]
    private ?string $cognoms;
    #[Column()]
    private ?string $pseudonim;
    #[Column]
    private string $ordenador;
    #[Column(name: 'data_naixement', type: 'date',)]
    private ?\DateTime $dataNaixement;
    #[Column(name: 'data_defuncio', type: 'date',)]
    private ?\DateTime $dataDefuncio;
    #[Column()]
    private ?string $nacionalitat;
    #[Column(type: 'text',)]
    private ?string $notes;
    #[ManyToMany(targetEntity: Obra::class, mappedBy: 'autors')]
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

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getCognoms(): string|null
    {
        return $this->cognoms;
    }

    public function setCognoms(string $cognoms): void
    {
        $this->cognoms = $cognoms;
    }

    public function getPseudonim(): string|null
    {
        return $this->pseudonim;
    }

    public function setPseudonim(string $pseudonim): void
    {
        $this->pseudonim = $pseudonim;
    }

    public function getOrdenador(): string
    {
        return $this->ordenador;
    }

    public function setOrdenador(string $ordenador): void
    {
        $this->ordenador = $ordenador;
    }

    public function getDataNaixement(): \DateTime|null
    {
        return $this->dataNaixement;
    }

    public function setDataNaixement(\DateTime $dataNaixement): void
    {
        $this->dataNaixement = $dataNaixement;
    }

    public function getDataDefuncio(): \DateTime|null
    {
        return $this->dataDefuncio;
    }

    public function setDataDefuncio(\DateTime $dataDefuncio): void
    {
        $this->dataDefuncio = $dataDefuncio;
    }

    public function getNacionalitat(): string|null
    {
        return $this->nacionalitat;
    }

    public function setNacionalitat(string $nacionalitat): void
    {
        $this->nacionalitat = $nacionalitat;
    }

    public function getNotes(): string|null
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    public function getObres(): Collection
    {
        return $this->obres;
    }

    public function addObra(Obra $obra): void
    {
        $obra->addAutor($this);
        $this->obres->add($obra);
    }
}
