<?php

declare(strict_types=1);

namespace Kartalit\Models;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Kartalit\Interfaces\ModelInterface;

#[Entity]
#[Table(name: "llibre_user")]
class Biblioteca implements ModelInterface
{
    #[Id]
    #[ManyToOne(targetEntity: Llibre::class, inversedBy: "biblioteca")]
    #[JoinColumn(name: "llibre", referencedColumnName: "llibreID")]
    private Llibre $llibre;
    #[Id]
    #[ManyToOne(targetEntity: Usuari::class, inversedBy: "biblioteca")]
    #[JoinColumn(name: "user", referencedColumnName: "userID")]
    private Usuari $usuari;
    #[Column]
    private bool $privat = false;
    #[Column]
    private string $condicio = "Propi";
    #[Column]
    private ?string $obtingut = null;
    #[Column]
    private ?string $motiu = null;
    // Afegir lloc compra
    #[Column(type: "float", precision: 2)]
    private ?float $preu = null;
    #[Column(name: "estat_actual")]
    private ?string $estatActual = null;
    #[Column(type: "text")]
    private ?string $notes = null;
    #[Column(name: "data_obtencio", type: "date")]
    private ?DateTime $dataObtencio = null;

    #region Getters and setters
    public function getLlibre(): Llibre
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
    public function getCondicio(): string
    {
        return $this->condicio;
    }
    public function setCondicio(string $condicio): void
    {
        $this->condicio = $condicio;
    }
    public function getObtingut(): ?string
    {
        return $this->obtingut;
    }
    public function setObtingut(?string $obtingut): void
    {
        $this->obtingut = $obtingut;
    }
    public function getMotiu(): ?string
    {
        return $this->motiu;
    }
    public function setMotiu(?string $motiu): void
    {
        $this->motiu = $motiu;
    }
    public function getPreu(): ?float
    {
        return $this->preu;
    }
    public function setPreu(?float $preu): void
    {
        $this->preu = $preu;
    }
    public function getEstatActual(): ?string
    {
        return $this->estatActual;
    }
    public function setEstatActual(?string $estatActual): void
    {
        $this->estatActual = $estatActual;
    }
    public function getNotes(): ?string
    {
        return $this->notes;
    }
    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
    public function getDataObtencio(): ?DateTime
    {
        return $this->dataObtencio;
    }
    public function setDataObtencio(?DateTime $dataObtencio): void
    {
        $this->dataObtencio = $dataObtencio;
    }
    #endregion
    public function toArray(): array
    {
        return [
            "privat" => $this->getPrivat(),
            "condicio" => $this->getCondicio(),
            "obtingut" => $this->getObtingut(),
            "motiu" => $this->getMotiu(),
            "preu" => $this->getPreu(),
            "estatActual" => $this->getEstatActual(),
            "notes" => $this->getNotes(),
            "dataObtencio" => $this->getDataObtencio()?->format("Y-m-d"),
            "usuari" => $this->getUsuari()->getId(),
            "llibre" => $this->getLlibre()->getId(),
        ];
    }
}
