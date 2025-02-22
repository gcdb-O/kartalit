<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Kartalit\Enums\Entity;
use Kartalit\Models\Biblioteca;
use Kartalit\Models\Llibre;
use Kartalit\Models\Usuari;

class BibliotecaService extends EntityService
{
    protected static Entity $entity = Entity::BIBLIOTECA;

    public function getBibliotecaFromLlibreUser(Llibre|int $llibre, Usuari|int $usuari): ?Biblioteca
    {
        $llibreId = $llibre instanceof Llibre ? $llibre->getId() : $llibre;
        $usuariId = $usuari instanceof Usuari ? $usuari->getId() : $usuari;
        // TODO: Gestionar llibre privat
        return $this->repository->createQueryBuilder("b")
            ->select("b")
            ->where("b.llibre = :llibreId")
            ->andWhere("b.usuari = :usuariId")
            ->setParameters(
                new ArrayCollection(array(
                    new Parameter("llibreId", $llibreId),
                    new Parameter("usuariId", $usuariId)
                ))
            )
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function create(
        Llibre $llibre,
        Usuari $usuari,
        bool $privat = false
    ): Biblioteca {
        $biblioteca = new Biblioteca();
        $biblioteca->setLlibre($llibre);
        $biblioteca->setUsuari($usuari);
        $biblioteca->setPrivat($privat);

        $this->em->persist($biblioteca);
        $this->em->flush();

        return $biblioteca;
    }
}
