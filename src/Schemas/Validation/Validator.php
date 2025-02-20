<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Errors\InvalidTypeException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;

abstract class Validator
{
    protected static function execute(Validatable $validator, array $data): void
    {
        try {
            $validator->assert($data);
        } catch (NestedValidationException $th) {
            throw new InvalidTypeException($th->getMessages(), $th);
        }
    }
}
