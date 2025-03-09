<?php

declare(strict_types=1);

namespace Kartalit\Enums;

use Kartalit\Models\Autor;
use Kartalit\Models\Biblioteca;
use Kartalit\Models\Cita;
use Kartalit\Models\Esdeveniment;
use Kartalit\Models\Idioma;
use Kartalit\Models\Llegit;
use Kartalit\Models\Llibre;
use Kartalit\Models\MapaLiterari;
use Kartalit\Models\Obra;
use Kartalit\Models\Usuari;

enum Entity: string
{
    case AUTOR = "autor";
    case BIBLIOTECA = "biblioteca";
    case CITA = "cita";
    case ESDEVENIMENT = "esdeveniment";
    case IDIOMA = "idioma";
    case LLEGIT = "llegit";
    case LLIBRE = "llibre";
    case MAPA = "mapa";
    case OBRA = "obra";
    case USUARI = "usuari";

    public function getClassName(): string
    {
        return match ($this) {
            Entity::AUTOR => Autor::class,
            Entity::BIBLIOTECA => Biblioteca::class,
            Entity::CITA => Cita::class,
            Entity::ESDEVENIMENT => Esdeveniment::class,
            Entity::IDIOMA => Idioma::class,
            Entity::LLEGIT => Llegit::class,
            Entity::LLIBRE => Llibre::class,
            Entity::MAPA => MapaLiterari::class,
            Entity::OBRA => Obra::class,
            Entity::USUARI => Usuari::class,
        };
    }
    public function ambArticle(): string
    {
        return match ($this) {
            Entity::LLIBRE,
            Entity::LLEGIT,
            Entity::MAPA => 'el ' . $this->value,
            Entity::CITA,
            Entity::BIBLIOTECA => 'la ' . $this->value,
            Entity::AUTOR,
            Entity::ESDEVENIMENT,
            Entity::IDIOMA,
            Entity::OBRA,
            Entity::USUARI => "l'" . $this->value,
        };
    }
}
