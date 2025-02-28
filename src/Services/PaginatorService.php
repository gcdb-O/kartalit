<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Kartalit\Interfaces\ModelInterface;

class PaginatorService extends Paginator
{
    public function toArray(string|null $method = null): array
    {
        $result = [];
        foreach ($this as $item) {
            /** @var ModelInterface $item */
            array_push($result, $item->toArray());
        }
        return $result;
    }
}
