<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use InvalidArgumentException;
use Kartalit\Enums\HttpStatusCode;
use Throwable;

class BadRequestException extends InvalidArgumentException
{
    public function __construct(
        string $message = "",
        HttpStatusCode $code = HttpStatusCode::BAD_REQUEST,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code->value, $previous);
    }
}
