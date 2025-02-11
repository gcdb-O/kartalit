<?php

declare(strict_types=1);

namespace Kartalit\Models;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "llegits")]
class Llegit
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: "llegitID")]
    private int $id;
    #[Column(name: "comencat", type: "date")]
    private ?DateTime $dataComencat;
    #[Column(name: "acabat", type: "date")]
    private ?DateTime $dataAcabat;
    #[Column]
    private ?float $valoracio;
    #[Column]
    private bool $privat;
    #[Column(type: "text")]
    private ?string $notes;
    #[ManyToOne(targetEntity: Llibre::class, inversedBy: "llegits")]
    #[JoinColumn(name: "llibre", referencedColumnName: "llibreID")]
    private Llibre $llibre;
    #[ManyToOne(targetEntity: Usuari::class, inversedBy: "llegits")]
    #[JoinColumn(name: "user", referencedColumnName: "userID")]
    private ?Usuari $usuari;
    #region Getters and setters
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getDataComencat(): ?DateTime
    {
        return $this->dataComencat;
    }
    public function setDataComencat(?DateTime $dataComencat): void
    {
        $this->dataComencat = $dataComencat;
    }
    public function getDataAcabat(): ?DateTime
    {
        return $this->dataAcabat;
    }
    public function setDataAcabat(?DateTime $dataAcabat): void
    {
        $this->dataAcabat = $dataAcabat;
    }
    public function getValoracio(): ?float
    {
        return $this->valoracio;
    }
    public function setValoracio(?float $valoracio): void
    {
        $this->valoracio = $valoracio;
    }
    public function getPrivat(): bool
    {
        return $this->privat;
    }
    public function setPrivat(bool $privat): void
    {
        $this->privat = $privat;
    }
    public function getNotes(): ?string
    {
        return $this->notes;
    }
    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
    public function getLlibre(): Llibre
    {
        return $this->llibre;
    }
    public function setLlibre(Llibre $llibre): void
    {
        $this->llibre = $llibre;
    }
    public function getUsuari(): ?Usuari
    {
        return $this->usuari;
    }
    public function setUsuari(?Usuari $usuari): void
    {
        $this->usuari = $usuari;
    }
    #endregion
    public function getArray(): array
    {
        return [
            "id" => $this->getId(),
            "llibre" => $this->getLlibre()->getCobertesBasic(),
            "dataComencat" => $this->getDataComencat(),
            "dataAcabat" => $this->getDataAcabat(),
            "valoracio" => $this->getValoracio(),
            "notes" => $this->getNotes(),
            "privat" => $this->getPrivat(),
        ];
    }
}
