<?php

declare(strict_types=1);

namespace Kartalit\Enums;

enum Entity: string
{
    case AUTOR = "autor";
    case LLIBRE = "llibre";
    case OBRA = "obra";

    public function ambArticle(): string
    {
        return match ($this) {
            Entity::LLIBRE => 'el ' . $this->value,
            Entity::AUTOR,
            Entity::OBRA => "l'" . $this->value,
        };
    }
}
