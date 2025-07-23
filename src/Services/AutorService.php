<?php

declare(strict_types=1);

namespace Kartalit\Services;

use DateTime;
use Doctrine\Common\Collections\Order;
use Kartalit\Enums\Entity;
use Kartalit\Models\Autor;

class AutorService extends EntityService
{
    protected static Entity $entity = Entity::AUTOR;

    public function create(
        string $nom,
        ?string $cognoms,
        ?string $pseudonim,
        string $ordenador,
        ?DateTime $dataNaixement,
        ?DateTime $dataDefuncio,
        ?string $nacionalitat,
        ?string $notes
    ): Autor {
        $autor = new Autor();
        $autor->setNom($nom);
        $autor->setCognoms($cognoms);
        $autor->setPseudonim($pseudonim);
        $autor->setOrdenador($ordenador);
        $autor->setDataNaixement($dataNaixement);
        $autor->setDataDefuncio($dataDefuncio);
        $autor->setNacionalitat($nacionalitat);
        $autor->setNotes($notes);

        $this->em->persist($autor);
        $this->em->flush();

        return $autor;
    }
    public function getAllOrdenat($ordre = Order::Ascending): array
    {
        $qb = $this->repository->createQueryBuilder("a");
        $qb->orderBy("a.ordenador", $ordre->value);
        return $qb->getQuery()->getResult();
    }
}
