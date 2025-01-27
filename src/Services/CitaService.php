<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Cita;

class CitaService extends EntityService
{
    protected static string $entity = Cita::class;

    public function getRandom()
    {
        // TODO: Excloure etiqueta mapa_literari
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(self::$entity, 'c')
            ->where('c.privat != 1')
            ->orderBy('RANDOMSORT()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
