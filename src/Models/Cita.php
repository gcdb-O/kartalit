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
#[Table(name: "cites")]
class Cita
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: "citaID")]
    private int $id;
    #[ManyToOne(targetEntity: Obra::class, inversedBy: "cites")]
    #[JoinColumn(name: "obra", referencedColumnName: "obraID")]
    private ?Obra $obra;
    #[ManyToOne(targetEntity: Llibre::class, inversedBy: "cites")]
    #[JoinColumn(name: "llibre", referencedColumnName: "llibreID")]
    private ?Llibre $llibre;
    #[ManyToOne(targetEntity: Usuari::class, inversedBy: "cites")]
    #[JoinColumn(name: "user", referencedColumnName: "userID")]
    private Usuari $usuari;
    #[Column]
    private bool $privat;
    #[Column(type: "smallint")]
    private ?int $pagina;
    #[Column(type: "smallint")]
    private ?int $ordre = null;
    #[Column(type: "text")]
    private ?string $cita;
    #[Column(type: "text")]
    private ?string $comentari;

    #region Getters and setters
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getObra(): ?Obra
    {
        return $this->obra;
    }
    public function setObra(Obra $obra): void
    {
        $this->obra = $obra;
    }
    public function getLlibre(): ?Llibre
    {
        return $this->llibre;
    }
    public function setLlibre(Llibre $llibre): void
    {
        $this->llibre = $llibre;
    }
    public function getUsuari(): Usuari
    {
        return $this->usuari;
    }
    public function setUsuari(Usuari $usuari): void
    {
        $this->usuari = $usuari;
    }
    public function getPrivat(): bool
    {
        return $this->privat;
    }
    public function setPrivat(bool $privat): void
    {
        $this->privat = $privat;
    }
    public function getPagina(): ?int
    {
        return $this->pagina;
    }
    public function setPagina(?int $pagina): void
    {
        $this->pagina = $pagina;
    }
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }
    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }
    public function getCita(): ?string
    {
        return $this->cita;
    }
    public function setCita(?string $cita): void
    {
        $this->cita = $cita;
    }
    public function getComentari(): ?string
    {
        return $this->comentari;
    }
    public function setComentari(?string $comentari): void
    {
        $this->comentari = $comentari;
    }
    #endregion
    public function getArray(): array
    {
        return [
            "id" => $this->getId(),
            "privat" => $this->getPrivat(),
            "pagina" => $this->getPagina(),
            "ordre" => $this->getOrdre(),
            "cita" => $this->getCita(),
            "comentari" => $this->getComentari(),
        ];
    }
    public function getCitaObraArray(): array
    {
        $obra = $this->getObra();
        return [
            "cita" => $this->getCita(),
            "obra" => [
                "id" => $obra?->getId(),
                "titolOriginal" => $obra?->getTitolOriginal(),
                "titolCatala" => $obra?->getTitolCatala(),
                "autors" => array_map(function (Autor $autor) {
                    return [
                        "id" => $autor->getId(),
                        "nomComplet" => $autor->getNomComplet(),
                        "pseudonim" => $autor->getPseudonim(),
                    ];
                }, $obra->getAutors()->toArray()),
            ]
        ];
    }
}
