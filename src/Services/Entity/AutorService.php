<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use DateTime;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\QueryBuilder;
use Kartalit\Enums\Entity;
use Kartalit\Models\Autor;
use Kartalit\Services\PaginatorService;

class AutorService extends EntityService
{
    protected static Entity $entity = Entity::AUTOR;

    public function create(
        string $nom,
        ?string $cognoms,
        ?string $pseudonim,
        string $ordenador,
        ?DateTime $dataNaixement,
        ?DateTime $dataDefuncio,
        ?string $nacionalitat,
        ?string $notes
    ): Autor {
        $autor = new Autor();
        $autor->setNom($nom);
        $autor->setCognoms($cognoms);
        $autor->setPseudonim($pseudonim);
        $autor->setOrdenador($ordenador);
        $autor->setDataNaixement($dataNaixement);
        $autor->setDataDefuncio($dataDefuncio);
        $autor->setNacionalitat($nacionalitat);
        $autor->setNotes($notes);

        $this->em->persist($autor);
        $this->em->flush();

        return $autor;
    }
    public function getAllOrdenat($ordre = Order::Ascending): array
    {
        $qb = $this->qbAllOrdered($ordre);
        return $qb->getQuery()->getResult();
    }
    public function getAllPaginated(int $limit = 10, int $pagina = 1, Order $ordre = Order::Ascending): PaginatorService
    {
        $firstResult = $pagina * $limit;
        $qb = $this->qbAllOrdered(order: $ordre);
        $query = $qb->getQuery()->setFirstResult($firstResult)->setMaxResults($limit);
        // var_dump($query->getSQL());
        return new PaginatorService($query, false);
    }
    private function qbAllOrdered(Order $order = Order::Ascending, string $autor = "a"): QueryBuilder
    {
        return $this->repository->createQueryBuilder($autor)
            ->orderBy("{$autor}.ordenador", $order->value);
    }
}
