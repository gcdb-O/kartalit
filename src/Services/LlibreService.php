<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\ORM\Query\Expr;
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
    public function getByIdWithCites(int $id, ?int $usuariId = null): ?Llibre
    {
        $qb = $this->repository->createQueryBuilder("l");
        $qb = $qb->where("l.id = :id")->setParameter("id", $id);
        // Ajuntar cites
        $qb = $qb->leftJoin("l.cites", "c", Expr\Join::WITH, $qb->expr()->orX(
            $qb->expr()->eq("c.usuari", ":usuariId"),
            $qb->expr()->eq("c.privat", "0")
        ))->setParameter("usuariId", $usuariId);
        $qb = $qb->select("l, c");
        // var_dump($qb->getQuery()->getArrayResult());
        return $qb->getQuery()->getOneOrNullResult();
    }
}
