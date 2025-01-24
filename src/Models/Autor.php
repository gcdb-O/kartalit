<?php

declare(strict_types=1);

namespace Kartalit\Models;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity]
#[Table(name: 'autors')]
class Autor
{
    #[Id]
    #[Column(name: 'autorID'), GeneratedValue]
    private int $id;

    #[Column]
    public string $nom;

    #[Column]
    public string $cognoms;

    #[Column]
    private string $pseudonim;

    #[Column]
    private string $ordenador;

    #[Column(name: 'data_naixement', type: 'date')]
    private \DateTime $dataNaixement;

    #[Column(name: 'data_defuncio', type: 'date')]
    private \DateTime $dataDefuncio;

    #[Column]
    private string $nacionalitat;

    #[Column(type: 'text')]
    private string $notes;
}
