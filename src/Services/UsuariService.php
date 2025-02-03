<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Usuari;

class UsuariService extends EntityService
{
    protected static string $entity = Usuari::class;

    public function getByUsername(string $username): ?Usuari
    {
        return $this->em->createQueryBuilder()
            ->select('u')
            ->from(self::$entity, 'u')
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
