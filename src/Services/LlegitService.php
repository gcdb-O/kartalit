<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Kartalit\Models\Llegit;
use Kartalit\Models\Usuari;

class LlegitService extends EntityService
{
    protected static string $entity = Llegit::class;

    /**
     * @param \Kartalit\Models\Usuari|int $usuari L'usuari que ha fet les lectures
     * @param \Kartalit\Models\Usuari|int|null $reqUser L'usuari que fa la petició
     */
    public function getByUsuari(Usuari|int $usuari, Usuari|int|null $reqUser)
    {
        $usuariId = $usuari instanceof Usuari ? $usuari->getId() : $usuari;
        $reqUserId = $reqUser instanceof Usuari ? $reqUser->getId() : $reqUser;
        $qb = $this->repository->createQueryBuilder("l")
            ->addSelect("CASE WHEN l.dataAcabat IS NULL THEN 1 ELSE 0 END AS HIDDEN acabatNull")
            ->where("l.usuari = :usuariId")
            ->setParameter("usuariId", $usuariId)
            ->join("l.llibre", "ll")
            ->orderBy("acabatNull", "DESC")
            ->addOrderBy("l.dataAcabat", "DESC");
        // No extreure els llibres privats dels altres.
        if ($usuariId !== $reqUserId) {
            $qb = $qb->andWhere("l.privat = 0");
        }
        return $qb->getQuery()->getResult();
    }
}
