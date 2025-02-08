<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Obra;

class ObraService extends EntityService
{
    protected static string $entity = Obra::class;
}
