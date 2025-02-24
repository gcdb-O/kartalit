<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Enums\Entity;

class EntityNotFoundException extends NotFoundException
{
    public function __construct(Entity $entity, ?int $id = null)
    {
        $message = "No s'ha trobat cap " . $entity->value;
        $message .= $id === null ? "." : " amb id: " . (string) $id;
        parent::__construct($message);
    }
}
