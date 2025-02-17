<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\MapaLiterari;
use Kartalit\Models\Obra;
use Kartalit\Models\Usuari;

class MapaService extends EntityService
{
    protected static string $entity = MapaLiterari::class;

    public function getByObra(int $obraId, ?int $usuariId)
    {
        $qb = $this->repository->createQueryBuilder('m');
        $qb = $qb
            ->where('m.obra = :obraId')
            ->setParameter('obraId', $obraId);
        if ($usuariId) {
            $qb = $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('m.usuari', ':usuariId'),
                    $qb->expr()->eq('m.privat', '0'),
                )
            )->setParameter('usuariId', $usuariId);
        } else {
            $qb = $qb->andWhere('m.privat = 0');
        }
        return $qb
            ->getQuery()
            ->getResult();
    }
    public function create(
        Obra $obra,
        Usuari $usuari,
        float $latitud,
        float $longitud,
        string $comentari,
        ?string $adreca = null,
        ?string $tipus = null,
        bool $privat = false,
        bool $precisio = false,
    ): MapaLiterari {
        $nouMapa = new MapaLiterari();
        $nouMapa->setObra($obra);
        $nouMapa->setUsuari($usuari);
        $nouMapa->setLatitud($latitud);
        $nouMapa->setLongitud($longitud);
        $nouMapa->setComentari($comentari);
        $nouMapa->setAdreca($adreca);
        $nouMapa->setTipus($tipus);
        $nouMapa->setPrivat($privat);
        $nouMapa->setPrecisio($precisio);

        $this->em->persist($nouMapa);
        $this->em->flush();

        return $nouMapa;
    }
}
