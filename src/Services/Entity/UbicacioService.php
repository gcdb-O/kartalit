<?php

declare(strict_types=1);

namespace Kartalit\Services\Entity;

use Kartalit\Enums\Entity;
use Kartalit\Models\Ubicacio;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

class UbicacioService extends EntityService
{
    protected static Entity $entity = Entity::UBICACIO;

    public function getSmallest(float $lat, float $lon): Ubicacio|null
    {
        $point = new Point($lat, $lon);
        $point->setLatitude($lat)->setLongitude($lon);
        return $this->repository->createQueryBuilder("u")
            ->join("u.ubicacioGeo", "ug")
            ->where("ST_Contains(ug.poligon, :point) = 1")
            ->setParameter("point", $point, "point")
            ->orderBy("u.nivell", "DESC")
            ->select("u")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
