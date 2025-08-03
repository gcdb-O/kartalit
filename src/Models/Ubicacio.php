<?php

declare(strict_types=1);

namespace Kartalit\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
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
    #[ManyToOne(targetEntity: Ubicacio::class, fetch: "EXTRA_LAZY")]
    #[JoinColumn(name: "parent", referencedColumnName: "ubicacioID")]
    private ?Ubicacio $parent = null;
    #[ManyToOne(targetEntity: Ubicacio::class, fetch: "EXTRA_LAZY")]
    #[JoinColumn(name: "pais", referencedColumnName: "ubicacioID")]
    private ?Ubicacio $pais = null;
    #[Column]
    private ?string $bandera = null;
    #[Column]
    private ?int $osm = null;
    #[OneToOne(targetEntity: UbicacioGeo::class, inversedBy: "ubicacio", fetch: "EXTRA_LAZY")]
    #[JoinColumn(name: "ubicacioID", referencedColumnName: "ubicacioID")]
    private ?UbicacioGeo $ubicacioGeo = null;
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
        return $this->ubicacioGeo?->getPoligon();
    }
    public function setPoligon(?MultiPolygon $poligon): void
    {
        $this->ubicacioGeo?->setPoligon($poligon);
    }
    #endregion
    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "nom" => $this->getNom(),
            "nomOriginal" => $this->getNomOriginal(),
            "nomLlarg" => $this->nom_llarg(),
            "nomLlargOriginal" => $this->nom_llarg(true),
            "nivell" => $this->getNivell(),
            "parent" => $this->getParent()?->getId() ?? null,
            "pais" => $this->getPais()?->getId() ?? null,
            "bandera" => $this->getBandera(),
            "osm" => $this->getOsm(),
        ];
    }
    public function nom_llarg($original = false): array
    {
        $nom_llarg = [];
        $parent = $this;
        while ($parent != null) {
            array_push($nom_llarg, $original ? $parent->getNomOriginal() ?? "" : $parent->getNom() ?? "");
            $parent = $parent->getParent();
        }
        return $nom_llarg;
    }
}
