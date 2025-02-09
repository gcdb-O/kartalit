<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Cita;

class CitaService extends EntityService
{
    protected static string $entity = Cita::class;

    public function getRandom(): Cita
    {
        // TODO: Excloure etiqueta mapa_literari
        return $this->repository->createQueryBuilder('c')
            ->where('c.privat = 0')
            ->join('c.obra', 'o')
            ->join('o.autors', 'a')
            ->orderBy('RANDOMSORT()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
    public function getByLlibre(int $llibreId, ?int $usuariId)
    {
        $qb = $this->repository->createQueryBuilder('c');
        $qb = $qb
            ->where('c.llibre = :llibreId')
            ->setParameter('llibreId', $llibreId);
        if ($usuariId) {
            $qb = $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('c.usuari', ':usuariId'),
                    $qb->expr()->eq('c.privat', '0'),
                )
            )->setParameter('usuariId', $usuariId);
        } else {
            $qb = $qb->andWhere('c.privat = 0');
        }
        return $qb->getQuery()->getResult();
    }
}
