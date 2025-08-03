<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Kartalit\Enums\Entity;
use Kartalit\Models\Biblioteca;
use Kartalit\Models\Llibre;
use Kartalit\Models\Usuari;

class BibliotecaService extends EntityService
{
    protected static Entity $entity = Entity::BIBLIOTECA;

    public function create(
        Llibre $llibre,
        Usuari $usuari,
        bool $privat = false,
        string $condicio = "Propi",
        ?string $obtingut = null,
        ?string $motiu = null,
        ?float $preu = null,
        ?DateTime $dataObtencio = null,
        ?string $estatActual = null,
        ?string $notes = null,
    ): Biblioteca {
        $biblioteca = new Biblioteca();
        $biblioteca->setLlibre($llibre);
        $biblioteca->setUsuari($usuari);
        $biblioteca->setPrivat($privat);
        $biblioteca->setCondicio($condicio);
        $biblioteca->setObtingut($obtingut);
        $biblioteca->setMotiu($motiu);
        $biblioteca->setPreu($preu);
        $biblioteca->setDataObtencio($dataObtencio);
        $biblioteca->setEstatActual($estatActual);
        $biblioteca->setNotes($notes);

        $this->em->persist($biblioteca);
        $this->em->flush();

        return $biblioteca;
    }
    public function getBibliotecaFromLlibreUser(Llibre|int $llibre, Usuari|int $usuari, Usuari|int|null $reqUser): ?Biblioteca
    {
        $llibreId = $llibre instanceof Llibre ? $llibre->getId() : $llibre;
        $usuariId = $usuari instanceof Usuari ? $usuari->getId() : $usuari;
        $reqUserId = $reqUser instanceof Usuari ? $reqUser->getId() : $reqUser;

        $qb = $this->repository->createQueryBuilder("b")
            ->where("b.llibre = :llibreId")
            ->andWhere("b.usuari = :usuariId")
            ->setParameters(
                new ArrayCollection(array(
                    new Parameter("llibreId", $llibreId),
                    new Parameter("usuariId", $usuariId)
                ))
            );
        if ($usuariId !== $reqUserId) {
            $qb = $qb->andWhere("b.privat = 0");
        }
        return $qb->getQuery()
            ->getOneOrNullResult();
    }
}
