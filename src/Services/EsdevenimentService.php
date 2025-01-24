<?php

declare(strict_types=1);

namespace Kartalit\Services;

use DateTime;
use Kartalit\Models\Esdeveniment;

class EsdevenimentService extends EntityService
{
    protected static string $entity = Esdeveniment::class;

    public function getByDiaMes(DateTime $data = new DateTime())
    {
        $dia = intval($data->format('j'));
        $mes = intval($data->format('n'));
        return $this->em->createQueryBuilder()
            ->select('e', 'o')
            ->from(self::$entity, 'e')
            ->join('e.obra', 'o')
            ->where('e.dia = :dia')
            ->andWhere('e.mes = :mes')
            ->setParameter('dia', $dia)
            ->setParameter('mes', $mes)
            ->getQuery()
            ->getResult();
    }
}
