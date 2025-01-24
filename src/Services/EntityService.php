<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\ORM\EntityManager;

abstract class EntityService
{
    protected static string $entity;
    public function __construct(protected EntityManager $em) {}

    public function getById(int $id)
    {
        return $this->em->find(static::$entity, $id);
    }
}
