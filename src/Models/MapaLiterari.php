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
#[Table(name: "mapa_literari")]
class MapaLiterari
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: "mapaID")]
    private int $id;
    #[ManyToOne(targetEntity: Obra::class, inversedBy: "mapaLiterari")]
    #[JoinColumn(name: "llibre", referencedColumnName: "obraID")]
    private Obra $obra;
    #[ManyToOne(targetEntity: Usuari::class, inversedBy: "mapaLiterari")]
    #[JoinColumn(name: "user", referencedColumnName: "userID")]
    private Usuari $usuari;
    #[Column]
    private bool $privat;
    #[Column]
    private ?string $tipus;
    #[Column]
    private bool $precisio;
    #[Column(name: "coordenada_X")]
    private float $longitud;
    #[Column(name: "coordenada_Y")]
    private float $latitud;
    #[Column]
    private ?string $adreca;
    #[Column(type: "text")]
    private ?string $comentari;
    //TODO: Afegir ubicacio

    #region Getters and setters
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getObra(): Obra
    {
        return $this->obra;
    }
    public function setObra(Obra $obra): void
    {
        $this->obra = $obra;
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
    public function getTipus(): ?string
    {
        return $this->tipus;
    }
    public function setTipus(?string $tipus): void
    {
        $this->tipus = $tipus;
    }
    public function getPrecisio(): bool
    {
        return $this->precisio;
    }
    public function setPrecisio(bool $precisio): void
    {
        $this->precisio = $precisio;
    }
    public function getLongitud(): float
    {
        return $this->longitud;
    }
    public function setLongitud(float $longitud): void
    {
        $this->longitud = $longitud;
    }
    public function getLatitud(): float
    {
        return $this->latitud;
    }
    public function setLatitud(float $latitud): void
    {
        $this->latitud = $latitud;
    }
    public function getAdreca(): ?string
    {
        return $this->adreca;
    }
    public function setAdreca(?string $adreca): void
    {
        $this->adreca = $adreca;
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
    public function getArray(bool $extraInfo = false): array
    {
        $mapa = [
            "obra" => [
                "id" => $this->obra->getId(),
                "titolOriginal" => $this->obra->getTitolOriginal(),
                "titolCatala" => $this->obra->getTitolCatala(),
            ],
            "tipus" => $this->getTipus(),
            "precisio" => $this->getPrecisio(),
            "longitud" => $this->getLongitud(),
            "latitud" => $this->getLatitud(),
            "adreca" => $this->getAdreca(),
            "comentari" => $this->getComentari(),
            "privat" => $this->getPrivat(),
            "usuari" => $this->getUsuari()->getId()
        ];
        if ($extraInfo) {
            $mapa["obra"]["autors"] = array_map(fn(Autor $autor) => [
                "id" => $autor->getId(),
                "nomComplet" => $autor->getNomComplet(),
                "pseudonim" => $autor->getPseudonim(),
            ], $this->obra->getAutors()->toArray());
            // $mapa["obra"]["coberta"] = $this->obra->getLlibres()?->first()?->getCobertaBase64();
        }
        return $mapa;
    }
}
