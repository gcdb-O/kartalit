<?php

declare(strict_types=1);

namespace Kartalit\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Kartalit\Interfaces\ModelInterface;
use LongitudeOne\Spatial\PHP\Types\Geometry\MultiPolygon;

#[Entity]
#[Table(name: "ubicacio")]
class UbicacioGeo implements ModelInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: "ubicacioID", type: "smallint", options: ["unsigned" => true])]
    private int $id;
    #[OneToOne(targetEntity: Ubicacio::class, mappedBy: "ubicacioGeo", fetch: "EXTRA_LAZY")]
    private ?Ubicacio $ubicacio = null;
    #[Column(type: "multipolygon")]
    private ?MultiPolygon $poligon = null;
    #region Getters and setters
    public function getId(): int
    {
        return $this->id;
    }
    public function getPoligon(): ?MultiPolygon
    {
        return $this->poligon;
    }
    public function setPoligon(?MultiPolygon $poligon): void
    {
        $this->poligon = $poligon;
    }
    # endregion
    public function toArray(): array
    {
        return [
            "ubicacioID" => $this->getId(),
        ];
    }
}
