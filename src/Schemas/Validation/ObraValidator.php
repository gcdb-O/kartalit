<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Interfaces\ValidatorSchemaInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as V;

class ObraValidator extends Validator implements ValidatorSchemaInterface
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

    public static function post(): Validatable
    {
        return V::key("titol_original", V::stringType()->length(1, 150), true)
            ->key("titol_catala", V::optional(V::stringType()->length(1, 150)), false)
            ->key("any_publicacio", V::optional(V::intVal()), false)
            ->key("idioma_original", V::intVal()->min(0), true)
            ->key("obra_autor", V::optional(V::intVal()), false);
    }
}
