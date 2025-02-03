<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Llibre;

class LlibreService extends EntityService
{
    protected static string $entity = Llibre::class;

    /**
     * Retorna els últims llibres afegits
     */
    public function getNous(int $limit = 8)
    {
        return $this->em->createQueryBuilder()
            ->select("l")
            ->from(self::$entity, "l")
            //TODO: treure aquesta condició i gestionar el front
            ->where("l.coberta IS NOT NULL")
            ->orderBy("l.id", "DESC")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
