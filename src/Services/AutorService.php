<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Models\Autor;

class AutorService extends EntityService
{
    protected static string $entity = Autor::class;
}
