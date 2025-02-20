<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

interface ValidatorSchemaInterface
{
    public static function validate(array $data, ?string $type = null): void;
}
