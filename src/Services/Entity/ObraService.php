<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use Doctrine\ORM\EntityManager;
use Kartalit\Enums\Entity;
use Kartalit\Helpers\DataValidation as DV;
use Kartalit\Models\Autor;
use Kartalit\Models\Idioma;
use Kartalit\Models\Obra;

class ObraService extends EntityService
{
    protected static Entity $entity = Entity::OBRA;

    public function __construct(
        protected EntityManager $em,
        private AutorService $autorService,
        private IdiomaService $idiomaService
    ) {
        parent::__construct($em);
    }

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
    public function createFromObject(array|object $obraObject): Obra
    {
        return $this->create(
            titolOriginal: (string) $obraObject["titol_original"],
            titolCatala: DV::issetAndNotEmptyString($obraObject, "titol_catala") ? (string) $obraObject["titol_catala"] : null,
            anyPublicacio: DV::issetAndNotEmptyString($obraObject, "any_publicacio") ? (int) $obraObject["any_publicacio"] : null,
            idiomaOriginal: $this->idiomaService->getById((int) $obraObject["idioma_original"], true),
            autor: DV::issetAndNotEmptyString($obraObject, "autor") ? $this->autorService->getById((int) $obraObject["autor"]) : null
        );
    }
    // TODO: getAllOrdenat. Per a usar a la pàgina d'afegir llibre nou.
}
