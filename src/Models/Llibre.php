<?php

declare(strict_types=1);

namespace Kartalit\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
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
    #[ManyToOne(targetEntity: Idioma::class)]
    #[JoinColumn(name: "idioma", referencedColumnName: "idiomaID")]
    private Idioma $idioma;
    #[ManyToMany(targetEntity: Obra::class, inversedBy: "llibres")]
    #[JoinTable(
        name: "obres_llibres",
        joinColumns: new JoinColumn(name: 'llibre', referencedColumnName: 'llibreID'),
        inverseJoinColumns: new JoinColumn(name: 'obra', referencedColumnName: 'obraID')
    )]
    private Collection $obres;
    #[OneToMany(targetEntity: Biblioteca::class, mappedBy: 'llibre')]
    private Collection $biblioteca;
    #[OneToMany(targetEntity: Cita::class, mappedBy: "llibre")]
    private Collection $cites;

    public function __construct()
    {
        $this->obres = new ArrayCollection();
        $this->biblioteca = new ArrayCollection();
        $this->cites = new ArrayCollection();
    }
    #region Getters and setters
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
    public function getObres(): Collection
    {
        return $this->obres;
    }
    public function addObra(Obra $obra): void
    {
        $obra->addLlibre($this);
        $this->obres->add($obra);
    }
    public function getBiblioteca(): Collection
    {
        return $this->biblioteca;
    }
    public function addBiblioteca(Biblioteca $biblioteca): void
    {
        $biblioteca->setLlibre($this);
        $this->biblioteca->add($biblioteca);
    }
    public function getCites(): Collection
    {
        return $this->cites;
    }
    public function addCita(Cita $cita): void
    {
        $cita->setLlibre($this);
        $this->cites->add($cita);
    }
    #endregion
    public function getAutors(): array
    {
        $autors = [];
        /** @var Obra $obra */
        foreach ($this->getObres() as $obra) {
            /** @var Autor $autor */
            foreach ($obra->getAutors() as $autor) {
                array_push($autors, [
                    "id" => $autor->getId(),
                    "nomComplet" => $autor->getNomComplet(),
                    "pseudonim" => $autor->getPseudonim(),
                ]);
            }
        }
        return array_unique($autors, SORT_REGULAR);
    }
    public function getCobertaBase64(): string|bool|null
    {
        return is_resource($this->getCoberta()) ? base64_encode(stream_get_contents($this->getCoberta(), -1, 0)) : null;
    }
    public function getArray(): array
    {
        return [
            "id" => $this->getId(),
            "titol" => $this->getTitol(),
            "autors" => $this->getAutors(),
            "coberta" => $this->getCobertaBase64(),
            "isbn" => $this->getIsbn(),
            "editorial" => $this->getEditorial(),
            "pagines" => $this->getPagines(),
            "idioma" => $this->getIdioma()->getIdioma(),
        ];
    }
    public function getCobertesBasic(): array
    {
        return [
            "id" => $this->getId(),
            "titol" => $this->getTitol(),
            "coberta" => $this->getCobertaBase64(),
            "autors" => $this->getAutors(),
        ];
    }
}
