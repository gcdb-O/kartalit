<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use DateTime;
use Kartalit\Enums\Entity;

class EsdevenimentService extends EntityService
{
    protected static Entity $entity = Entity::ESDEVENIMENT;

    public function getByDiaMes(DateTime $data = new DateTime())
    {
        $dia = intval($data->format('j'));
        $mes = intval($data->format('n'));
        return $this->repository->createQueryBuilder("e")
            ->select('e', 'o')
            ->join('e.obra', 'o')
            ->where('e.dia = :dia')
            ->andWhere('e.mes = :mes')
            ->setParameter('dia', $dia)
            ->setParameter('mes', $mes)
            ->getQuery()
            ->getResult();
    }
}
