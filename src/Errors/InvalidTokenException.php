<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Exception;
use Kartalit\Enums\HttpStatusCode;
use Throwable;

class InvalidTokenException extends Exception
{
    public function __construct(
        string $message = "Token és invàlid.",
        HttpStatusCode $code = HttpStatusCode::UNAUTHORIZED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code->value, $previous);
    }
}
