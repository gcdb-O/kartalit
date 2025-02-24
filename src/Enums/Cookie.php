<?php

declare(strict_types=1);

namespace Kartalit\Enums;

enum Cookie: string
{
    case AUTH = "token";

    public function getDefaultExpTime(): int
    {
        return match ($this) {
            Cookie::AUTH,
            null => 3600 * 24 * 2
        };
    }
}
