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
        return $this->repository->createQueryBuilder("l")
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
        ))->orderBy("c.pagina", "ASC")->setParameter("usuariId", $usuariId);
        $qb = $qb->select("l, c");
        return $qb->getQuery()->getOneOrNullResult();
    }
    public function search(string $token, int $limit = 5)
    {
        $isNumeric = is_numeric($token);
        //TODO: Optimitzar cerques?
        $qb = $this->repository->createQueryBuilder("l")
            ->join("l.obres", "o")
            ->join("o.autors", "a");
        $qb = $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like("l.titol", ":token"),
                $qb->expr()->like("l.isbn", ":token"),
                $qb->expr()->like("o.titolOriginal", ":token"),
                $qb->expr()->like("a.ordenador", ":token"),
            )
        )
            ->setParameter("token", "%" . $token . "%")
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }
}
