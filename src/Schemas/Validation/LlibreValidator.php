<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Interfaces\ValidatorSchemaInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\ChainedValidator;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as V;

class LlibreValidator extends Validator implements ValidatorSchemaInterface
{
    public static function validate(Request &$request, ?string $type = null): void
    {
        switch ($type) {
            case "post":
                $validator = self::post();
                $data = $request->getParsedBody();
                parent::execute($validator, $data);
                break;
        }
    }

    private static function post(): Validatable
    {
        /** @var ChainedValidator $obraValidator */
        $obraValidator = ObraValidator::post();

        return V::key("titol", V::stringType()->length(1, 150), true)
            ->key("isbn", V::optional(V::stringType()->length(1, 25)), false)
            ->key("editorial", V::optional(V::stringType()->length(1, 50)), false) // TODO: Fer un trim a 50
            ->key("pagines", V::optional(V::intVal()->min(0)), false)
            ->key("idioma", V::intVal()->min(0), true)
            ->key("obtingut", V::optional(V::stringType()->length(1, 25)), false)
            ->key("motiu", V::optional(V::stringType()->length(1, 25)), false)
            ->key("condicio", V::stringType()->length(1, 20), true)
            ->key("data_obtencio", V::optional(V::date()), false)
            ->key("preu", V::optional(V::floatVal()), false)
            ->key("obra", V::oneOf(
                V::intVal(),
                $obraValidator->key("autor", V::optional(V::intVal()), false),
            ), true);
    }
}
