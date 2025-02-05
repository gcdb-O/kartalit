<?php

declare(strict_types=1);

namespace Kartalit\Enums;

enum Entity: string
{
    case AUTOR = "autor";
    case LLIBRE = "llibre";

    public function ambArticle(): string
    {
        return match ($this) {
            Entity::LLIBRE => 'el ' . $this->value,
            Entity::AUTOR => "l'" . $this->value,
        };
    }
}
