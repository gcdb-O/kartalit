<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Kartalit\Enums\Entity;
use Kartalit\Errors\EntityNotFoundException;
use Kartalit\Interfaces\EntityServiceInterface;

abstract class EntityService implements EntityServiceInterface
{
    protected static Entity $entity;
    protected EntityRepository $repository;
    public function __construct(
        protected EntityManager $em
    ) {
        $this->repository = $this->em->getRepository(static::$entity->getClassName());
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }
    public function getById(int $id, bool $throw = false): ?object
    {
        $entityObject = $this->repository->find($id);
        if (!$entityObject && $throw) {
            throw new EntityNotFoundException(static::$entity, $id);
        }
        return $entityObject;
    }
    public function deleteById(int $id): void
    {
        $entity = $this->repository->find($id);
        $this->em->remove($entity);
        $this->em->flush();
    }
    public function persist(object $entity): void
    {
        $this->em->persist($entity);
    }
    public function persistAndFlush(object $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
