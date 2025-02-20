<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Throwable;

class InvalidTypeException extends BadRequestException
{
    public function __construct(
        private array $fields,
        ?Throwable $previous = null
    ) {
        parent::__construct(message: "Camps incorrectes", previous: $previous);
    }
    public function getFields(): array
    {
        return $this->fields;
    }
}
