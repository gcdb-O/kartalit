<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Cita;

class CitaService extends EntityService
{
    protected static string $entity = Cita::class;

    public function getRandom()
    {
        // TODO: Excloure etiqueta mapa_literari    // ->join('c.obra', 'o')
        return $this->repository->createQueryBuilder('c')
            ->where('c.privat != 1')
            ->join('c.obra', 'o')
            ->join('o.autors', 'a')
            ->orderBy('RANDOMSORT()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
