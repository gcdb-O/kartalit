<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\MapaLiterari;

class MapaService extends EntityService
{
    protected static string $entity = MapaLiterari::class;

    public function getByObra(int $obraId, ?int $usuariId)
    {
        $qb = $this->repository->createQueryBuilder('m');
        $qb = $qb
            ->select('m')
            ->where('m.obra = :obraId')
            ->setParameter('obraId', $obraId);
        if ($usuariId) {
            $qb = $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('m.usuari', ':usuariId'),
                    $qb->expr()->andX(
                        $qb->expr()->neq('m.usuari', ':usuariId'),
                        $qb->expr()->eq('m.privat', '0'),
                    )
                )
            )->setParameter('usuariId', $usuariId);
        } else {
            $qb = $qb->andWhere('m.privat = 0');
        }
        return $qb
            ->getQuery()
            ->getResult();
    }
}
