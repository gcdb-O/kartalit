<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Enums\Entity;
use Kartalit\Models\Autor;
use Kartalit\Models\Idioma;
use Kartalit\Models\Obra;

class ObraService extends EntityService
{
    protected static Entity $entity = Entity::OBRA;

    public function create(
        string $titolOriginal,
        ?string $titolCatala,
        ?int $anyPublicacio,
        Idioma $idiomaOriginal,
        ?Autor $autor
    ): Obra {
        $obra = new Obra();
        $obra->setTitolOriginal($titolOriginal);
        $obra->setTitolCatala($titolCatala);
        $obra->setAnyPublicacio($anyPublicacio);
        $obra->setIdiomaOriginal($idiomaOriginal);
        if ($autor) {
            $obra->addAutor($autor);
        }

        $this->em->persist($obra);
        $this->em->flush();

        return $obra;
    }
}
