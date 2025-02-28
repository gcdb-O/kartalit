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
use Kartalit\Interfaces\ModelInterface;

#[Entity]
#[Table(name: 'obres')]
class Obra implements ModelInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'obraID')]
    private int $id;
    #[Column(name: 'titol_original')]
    private ?string $titolOriginal;
    #[Column(name: 'titol_catala')]
    private ?string $titolCatala;
    #[Column(name: 'Any_publicacio')]
    private ?int $anyPublicacio;
    #[ManyToOne(targetEntity: Idioma::class, inversedBy: 'obres')]
    #[JoinColumn(name: 'idioma_original', referencedColumnName: 'idiomaID')]
    private ?Idioma $idiomaOriginal;
    #[ManyToMany(targetEntity: Autor::class, inversedBy: 'obres')]
    #[JoinTable(
        name: 'obres_autors',
        joinColumns: new JoinColumn(name: 'obra', referencedColumnName: 'obraID'),
        inverseJoinColumns: new JoinColumn(name: 'autor', referencedColumnName: 'autorID')
    )]
    private Collection $autors;
    #[ManyToMany(targetEntity: Llibre::class, mappedBy: 'obres')]
    private Collection $llibres;
    #[OneToMany(targetEntity: Cita::class, mappedBy: "obra")]
    private Collection $cites;
    #[OneToMany(targetEntity: MapaLiterari::class, mappedBy: "obra")]
    private Collection $mapaLiterari;
    #[OneToMany(targetEntity: Esdeveniment::class, mappedBy: 'obra')]
    private Collection $esdeveniments;

    public function __construct()
    {
        $this->autors = new ArrayCollection();
        $this->llibres = new ArrayCollection();
        $this->cites = new ArrayCollection();
        $this->mapaLiterari = new ArrayCollection();
        $this->esdeveniments = new ArrayCollection();
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
    public function getIdiomaOriginal(): ?Idioma
    {
        return $this->idiomaOriginal;
    }
    public function setIdiomaOriginal(Idioma $idioma): void
    {
        $this->idiomaOriginal = $idioma;
    }
    public function getAutors(): Collection
    {
        return $this->autors;
    }
    public function addAutor(Autor $autor): void
    {
        $autor->addObra($this);
        $this->autors->add($autor);
    }
    public function getLlibres(): Collection
    {
        return $this->llibres;
    }
    public function addLlibre(Llibre $llibre): void
    {
        $llibre->addObra($this);
        $this->llibres->add($llibre);
    }
    public function getCites(): Collection
    {
        return $this->cites;
    }
    public function addCita(Cita $cita): void
    {
        $cita->setObra($this);
        $this->cites->add($cita);
    }
    public function getMapaLiterari(): Collection
    {
        return $this->mapaLiterari;
    }
    public function addMapaLiterari(MapaLiterari $mapaLiterari): void
    {
        $mapaLiterari->setObra($this);
        $this->mapaLiterari->add($mapaLiterari);
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
    #endregion
    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "titolOriginal" => $this->getTitolOriginal(),
            "titolCatala" => $this->getTitolCatala(),
            "autors" => array_map(fn(Autor $autor) => [
                "id" => $autor->getId(),
                "nomComplet" => $autor->getNomComplet(),
                "pseudonim" => $autor->getPseudonim(),
            ], $this->getAutors()->toArray()),
            "anyPublicacio" => $this->getAnyPublicacio(),
            "idiomaOriginal" => $this->getIdiomaOriginal()?->getIdioma(),
        ];
    }
}
