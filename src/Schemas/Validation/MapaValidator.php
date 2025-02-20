<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Interfaces\ValidatorSchemaInterface;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as V;

class MapaValidator extends Validator implements ValidatorSchemaInterface
{
    public static function validate(array $data, ?string $type = null): void
    {
        $validator = match ($type) {
            "post" => self::post(),
            default => null,
        };
        if ($validator) {
            parent::execute($validator, $data);
        }
    }
    private static function post(): Validatable
    {
        return V::key("latitud", V::floatVal()->between(-90, 90))
            ->key("longitud", V::floatVal()->between(-180, 180))
            ->key("comentari", V::stringType())
            ->key("adreca", V::stringType()->length(null, 100), false)
            ->key("tipus", V::stringType()->length(null, 50), false)
            ->key("precisio", V::boolVal())
            ->key("privat", V::boolVal(), false);
    }
}
