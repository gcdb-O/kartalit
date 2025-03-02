<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Errors\InvalidTypeException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;

abstract class Validator
{
    protected static function execute(Validatable $validator, array $data, bool $silent = false): array
    {
        try {
            $validator->assert($data);
        } catch (NestedValidationException $th) {
            $messages = $th->getMessages();
            if (!$silent) {
                throw new InvalidTypeException($messages, $th);
            }
            foreach ($messages as $key => $_) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
