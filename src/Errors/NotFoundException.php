<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Enums\HttpStatusCode;

class NotFoundException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, code: HttpStatusCode::NOT_FOUND->value);
    }
}
