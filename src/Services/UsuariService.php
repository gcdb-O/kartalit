<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\ORM\EntityManager;
use Kartalit\Config\Config;
use Kartalit\Models\Usuari;

class UsuariService extends EntityService
{
    protected static string $entity = Usuari::class;

    //TODO: Mirar si aquesta injecció EM es pot fer diferent, aprofitant la del parent
    public function __construct(protected EntityManager $em, private Config $config)
    {
        // parent::__construct($this->em);
    }
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
