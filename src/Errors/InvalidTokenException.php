<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Enums\HttpStatusCode;
use Throwable;

class InvalidTokenException extends UnauthorizedException
{
    public function __construct(
        string $message = "Token és invàlid.",
        HttpStatusCode $code = HttpStatusCode::UNAUTHORIZED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
