<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Kartalit\Config\Config;
use Kartalit\Interfaces\EntityServiceInterface;

abstract class EntityService implements EntityServiceInterface
{
    protected static string $entity;
    protected EntityRepository $repository;
    public function __construct(
        protected Config $config,
        protected EntityManager $em
    ) {
        $this->repository = $this->em->getRepository(static::$entity);
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }
    public function getById(int $id): ?object
    {
        return $this->repository->find($id);
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
