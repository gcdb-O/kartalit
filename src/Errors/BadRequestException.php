<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use InvalidArgumentException;
use Kartalit\Enums\HttpResponseCode;
use Throwable;

class BadRequestException extends InvalidArgumentException
{
    public function __construct(
        string $message = "",
        HttpResponseCode $code = HttpResponseCode::BAD_REQUEST,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code->value, $previous);
    }
}
