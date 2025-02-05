<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Enums\HttpStatusCode;
use RuntimeException;
use Throwable;

class ForbiddenException extends RuntimeException
{
    public function __construct(
        string $message = "No tens permía per realitzar aquesta acció.",
        HttpStatusCode $code = HttpStatusCode::FORBIDDEN,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code->value, $previous);
    }
}
