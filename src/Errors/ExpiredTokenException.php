<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Enums\HttpStatusCode;
use Throwable;
use UnexpectedValueException;

class ExpiredTokenException extends UnexpectedValueException
{
    public function __construct(
        string $message = "El token ha caducat.",
        HttpStatusCode $code = HttpStatusCode::UNAUTHORIZED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code->value, $previous);
    }
}
