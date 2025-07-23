<?php

declare(strict_types=1);

namespace Kartalit\Helpers;

class DataValidation
{
    public static function issetAndNotEmptyString(array|object|null $data, string $key): bool
    {
        if (!isset($data[$key])) return false;
        return trim($data[$key]) !== "";
    }
}
