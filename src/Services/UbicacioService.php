<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Enums\Entity;
use Kartalit\Models\Ubicacio;
use LongitudeOne\Spatial\DBAL\Types\Geometry\PointType;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

class UbicacioService extends EntityService
{
    protected static Entity $entity = Entity::UBICACIO;

    public function getSmallest(float $lat, float $lon): Ubicacio|null
    {
        $point = new Point($lat, $lon);
        $point->setLatitude($lat)->setLongitude($lon);
        return $this->repository->createQueryBuilder("u")
            ->where("ST_Contains(u.poligon, :point) = 1")
            ->setParameter("point", $point, "point")
            ->orderBy("u.nivell", "DESC")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
