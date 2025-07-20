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
use Kartalit\Interfaces\ModelInterface;
use LongitudeOne\Spatial\PHP\Types\Geometry\MultiPolygon;

#[Entity]
#[Table(name: "ubicacio")]
class Ubicacio implements ModelInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'ubicacioID', type: "smallint", options: ["unsigned" => true])]
    private int $id;
    #[Column]
    private ?string $nom = null;
    #[Column(name: 'nom_original')]
    private ?string $nomOriginal = null;
    #[Column]
    private int $nivell;
    #[ManyToOne(targetEntity: Ubicacio::class)]
    #[JoinColumn(name: "parent", referencedColumnName: "ubicacioID")]
    private Ubicacio $parent;
    #[ManyToOne(targetEntity: Ubicacio::class)]
    #[JoinColumn(name: "pais", referencedColumnName: "ubicacioID")]
    private Ubicacio $pais;
    #[Column]
    private ?string $bandera = null;
    #[Column]
    private ?int $osm = null;
    #[Column(type: "multipolygon")]
    private ?MultiPolygon $poligon = null;
    #region Getters and setters
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }
    public function getNomOriginal(): ?string
    {
        return $this->nomOriginal;
    }
    public function setNomOriginal(?string $nomOriginal): void
    {
        $this->nomOriginal = $nomOriginal;
    }
    public function getNivell(): int
    {
        return $this->nivell;
    }
    public function setNivell(int $nivell): void
    {
        $this->nivell = $nivell;
    }
    public function getParent(): ?Ubicacio
    {
        return $this->parent;
    }
    public function setParent(?Ubicacio $parent): void
    {
        $this->parent = $parent;
    }
    public function getPais(): ?Ubicacio
    {
        return $this->pais;
    }
    public function setPais(?Ubicacio $pais): void
    {
        $this->pais = $pais;
    }
    public function getBandera(): ?string
    {
        return $this->bandera;
    }
    public function setBandera(?string $bandera): void
    {
        $this->bandera = $bandera;
    }
    public function getOsm(): ?int
    {
        return $this->osm;
    }
    public function setOsm(?int $osm): void
    {
        $this->osm = $osm;
    }
    public function getPoligon(): ?MultiPolygon
    {
        return $this->poligon;
    }
    public function setPoligon(?MultiPolygon $poligon): void
    {
        $this->poligon = $poligon;
    }
    #endregion
    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "nom" => $this->getNom(),
            "nomOriginal" => $this->getNomOriginal(),
            "nivell" => $this->getNivell(),
            "parent" => $this->getParent()?->getId(),
            "pais" => $this->getPais()?->getId(),
            "bandera" => $this->getBandera(),
            "osm" => $this->getOsm(),
        ];
    }
}
