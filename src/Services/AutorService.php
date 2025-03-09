<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\Common\Collections\Order;
use Kartalit\Enums\Entity;

class AutorService extends EntityService
{
    protected static Entity $entity = Entity::AUTOR;

    public function getAllOrdenat($ordre = Order::Ascending): array
    {
        $qb = $this->repository->createQueryBuilder("a");
        $qb->orderBy("a.ordenador", $ordre->value);
        return $qb->getQuery()->getResult();
    }
}
