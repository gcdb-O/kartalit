<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Interfaces\ValidatorSchemaInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as V;

class AutorValidator extends Validator implements ValidatorSchemaInterface
{
    public static function validate(Request &$request, ?string $type = null): void
    {
        $validator = match ($type) {
            "post" => self::post(),
            default => null,
        };
        if ($validator) {
            $data = $request->getParsedBody();
            parent::execute($validator, $data);
        }
    }

    private static function post(): Validatable
    {
        return V::key("nom", V::stringType()->length(1, 50), true)
            ->key("cognoms", V::stringType()->length(null, 50), false)
            ->key("pseudonim", V::stringType()->length(null, 50), false)
            ->key("ordenador", V::stringType()->length(1, 25), true)
            ->key("data_naixement", V::anyOf(V::date(), V::not(V::notEmpty())), false)
            ->key("data_defuncio", V::anyOf(V::date(), V::not(V::notEmpty())), false)
            ->key("nacionalitat", V::stringType()->length(null, 25), false)
            ->key("notes", V::stringType(), false);
    }
}
