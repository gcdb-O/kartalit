<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Enums\Entity;
use Kartalit\Models\Usuari;

class UsuariService extends EntityService
{
    protected static Entity $entity = Entity::USUARI;

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
