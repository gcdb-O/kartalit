<?php

declare(strict_types=1);

namespace Kartalit\Models;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "user")]
class Usuari
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: "userID")]
    private int $id;
    #[Column]
    private string $usuari;
    #[Column]
    private string $email;
    #[Column]
    private string $salt;
    #[Column]
    private string $hash;
    #[Column]
    private int $nivell;
    #[Column(name: "data_creacio", type: "date")]
    private DateTime $dataCreacio;
    #[OneToMany(targetEntity: Cita::class, mappedBy: "user")]
    private Collection $cites;

    public function __construct()
    {
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

    public function getUsuari(): string
    {
        return $this->usuari;
    }

    public function setUsuari(string $usuari): void
    {
        $this->usuari = $usuari;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getNivell(): int
    {
        return $this->nivell;
    }

    public function setNivell(int $nivell): void
    {
        $this->nivell = $nivell;
    }

    public function getDataCreacio(): DateTime
    {
        return $this->dataCreacio;
    }

    public function setDataCreacio(DateTime $dataCreacio): void
    {
        $this->dataCreacio = $dataCreacio;
    }
    public function getCites(): Collection
    {
        return $this->cites;
    }
    public function addCita(Cita $cita): void
    {
        $cita->setUsuari($this);
        $this->cites->add($cita);
    }
    #endregion
    public function getArray(): array
    {
        return [
            "id" => $this->getId(),
            "usuari" => $this->getUsuari(),
            "email" => $this->getEmail(),
            "nivell" => $this->getNivell(),
        ];
    }
}
