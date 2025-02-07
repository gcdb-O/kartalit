<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Kartalit\Models\Biblioteca;
use Kartalit\Models\Llibre;
use Kartalit\Models\Usuari;

class BibliotecaService extends EntityService
{
    protected static string $entity = Biblioteca::class;

    public function getBibliotecaFromLlibreUser(Llibre|int $llibre, Usuari|int $usuari): ?Biblioteca
    {
        $llibreId = $llibre instanceof Llibre ? $llibre->getId() : $llibre;
        $usuariId = $usuari instanceof Usuari ? $usuari->getId() : $usuari;
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
}
