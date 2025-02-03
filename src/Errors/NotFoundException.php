<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Enums\HttpResponseCode;

class NotFoundException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, code: HttpResponseCode::NOT_FOUND->value);
    }
}
