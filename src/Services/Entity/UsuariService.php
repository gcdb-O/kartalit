<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use Doctrine\ORM\EntityManager;
use Kartalit\Config\Config;
use Kartalit\Enums\Entity;
use Kartalit\Models\Usuari;

class UsuariService extends EntityService
{
    protected static Entity $entity = Entity::USUARI;

    public function __construct(
        protected EntityManager $em,
        private Config $config
    ) {
        parent::__construct($em);
    }

    public function getByUsername(string $username): ?Usuari
    {
        return $this->repository->createQueryBuilder("u")
            ->where('u.usuari = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function checkPassword(Usuari $usuari, string $password): bool
    {
        $hash = md5($this->config->server['salt'] . $usuari->getSalt() . $password);
        return $hash === $usuari->getHash();
    }
}
