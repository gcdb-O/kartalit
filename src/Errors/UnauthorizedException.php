<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Enums\HttpStatusCode;
use RuntimeException;
use Throwable;

class UnauthorizedException extends RuntimeException
{
    public function __construct(
        string $message = "Si us plau, inicia sessió.",
        HttpStatusCode $code = HttpStatusCode::UNAUTHORIZED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code->value, $previous);
    }
}
