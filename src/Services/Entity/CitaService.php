<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use Kartalit\Enums\Entity;
use Kartalit\Models\Cita;
use Kartalit\Models\Llibre;
use Kartalit\Models\Obra;
use Kartalit\Models\Usuari;

class CitaService extends EntityService
{
    protected static Entity $entity = Entity::CITA;

    public function getRandom(): Cita
    {
        // TODO: Excloure etiqueta mapa_literari
        return $this->repository->createQueryBuilder('c')
            ->where('c.privat = 0')
            ->join('c.obra', 'o')
            ->join('o.autors', 'a')
            ->orderBy('RANDOMSORT()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
    public function getByLlibre(int $llibreId, ?int $usuariId)
    {
        $qb = $this->repository->createQueryBuilder('c');
        $qb = $qb
            ->where('c.llibre = :llibreId')
            ->setParameter('llibreId', $llibreId);
        if ($usuariId) {
            $qb = $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('c.usuari', ':usuariId'),
                    $qb->expr()->eq('c.privat', '0'),
                )
            )->setParameter('usuariId', $usuariId);
        } else {
            $qb = $qb->andWhere('c.privat = 0');
        }
        return $qb->getQuery()->getResult();
    }
    public function create(
        string $cita,
        ?int $pagina,
        ?string $comentari,
        Usuari $usuari,
        Llibre $llibre,
        Obra $obra,
        bool $privat = false,
    ): Cita {
        $novaCita = new Cita();
        $novaCita->setCita($cita);
        $novaCita->setPagina($pagina);
        $novaCita->setComentari($comentari);
        $novaCita->setLlibre($llibre);
        $novaCita->setObra($obra);
        $novaCita->setUsuari($usuari);
        $novaCita->setPrivat($privat);

        $this->em->persist($novaCita);
        $this->em->flush();

        return $novaCita;
    }
}
