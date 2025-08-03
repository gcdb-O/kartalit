<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use Doctrine\ORM\Query\Expr;
use Kartalit\Enums\Entity;
use Kartalit\Errors\EntityNotFoundException;
use Kartalit\Models\Idioma;
use Kartalit\Models\Llibre;
use Kartalit\Models\Obra;
use Kartalit\Models\Usuari;
use Kartalit\Services\PaginatorService;

class LlibreService extends EntityService
{
    protected static Entity $entity = Entity::LLIBRE;

    public function create(
        string $titol,
        ?string $isbn,
        ?string $editorial,
        ?int $pagines,
        Idioma $idioma,
        ?Obra $obra
    ): Llibre {
        $llibre = new Llibre();
        $llibre->setTitol($titol);
        $llibre->setIsbn($isbn);
        $llibre->setEditorial($editorial);
        $llibre->setPagines($pagines);
        $llibre->setIdioma($idioma);
        if ($obra) {
            $llibre->addObra($obra);
        }

        $this->em->persist($llibre);
        $this->em->flush();

        return $llibre;
    }
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
    public function getAllPage(int $limit = 20, int $pagina = 0): PaginatorService
    {
        $firstResult = $pagina * $limit;
        $qb = $this->repository->createQueryBuilder("l");
        $qb->orderBy("l.id", "ASC");
        $query = $qb->getQuery()->setFirstResult($firstResult)->setMaxResults($limit);

        return new PaginatorService($query);
    }

    public function getByIdWithCites(int $id, ?int $usuariId = null, bool $throw = false): ?Llibre
    {
        $qb = $this->repository->createQueryBuilder("l");
        $qb = $qb->where("l.id = :id")->setParameter("id", $id);
        // Ajuntar cites
        $qb = $qb->leftJoin("l.cites", "c", Expr\Join::WITH, $qb->expr()->orX(
            $qb->expr()->eq("c.usuari", ":usuariId"),
            $qb->expr()->eq("c.privat", "0")
        ))->orderBy("c.pagina", "ASC")->setParameter("usuariId", $usuariId);
        $qb = $qb->select("l, c");
        $llibre = $qb->getQuery()->getOneOrNullResult();
        if (!$llibre && $throw) {
            throw new EntityNotFoundException(static::$entity, $id);
        }
        return $llibre;
    }
    public function getBibliotecaByUsuari(Usuari|int $usuari, Usuari|int|null $reqUser, int $limit = 20, int $pagina = 0): PaginatorService
    {
        $usuariId = $usuari instanceof Usuari ? $usuari->getId() : $usuari;
        $reqUserId = $reqUser instanceof Usuari ? $reqUser->getId() : $reqUser;

        $firstResult = $pagina * $limit;

        $qb = $this->repository->createQueryBuilder("l")
            ->innerJoin("l.biblioteca", "b", Expr\Join::WITH, "b.usuari = :usuariId")
            ->join("l.obres", "o")
            ->leftJoin("o.autors", "a")
            ->setParameter("usuariId", $usuariId);
        if ($usuariId !== $reqUserId) {
            $qb->andWhere("b.privat = 0");
        }
        $qb->groupBy("l.id")
            ->addOrderBy("a.ordenador", "ASC")
            ->addOrderBy("o.anyPublicacio", "ASC");
        $query = $qb->getQuery()->setMaxResults($limit)->setFirstResult($firstResult);
        return new PaginatorService($query, false);
    }
    public function search(string $token, int $limit = 5)
    {
        $isNumeric = is_numeric($token);
        //TODO: Optimitzar cerques?
        $qb = $this->repository->createQueryBuilder("l")
            ->join("l.obres", "o")
            ->leftJoin("o.autors", "a");
        $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like("l.titol", ":token"),
                $qb->expr()->like("l.isbn", ":token"),
                $qb->expr()->like("o.titolOriginal", ":token"),
                $qb->expr()->like("a.ordenador", ":token"),
            )
        )->setParameter("token", "%" . $token . "%");

        $qb->groupBy("l.id")
            ->addOrderBy("a.ordenador", "ASC")
            ->addOrderBy("o.anyPublicacio", "ASC")
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }
}
