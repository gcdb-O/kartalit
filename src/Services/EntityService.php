<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\ORM\EntityManager;
use Kartalit\Interfaces\EntityServiceInterface;

abstract class EntityService implements EntityServiceInterface
{
    protected static string $entity;
    public function __construct(protected EntityManager $em) {}

    public function getAll(): array
    {
        return $this->em->getRepository(static::$entity)->findAll();
    }
    public function getById(int $id): ?object
    {
        return $this->em->find(static::$entity, $id);
    }
}
