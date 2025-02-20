<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Interfaces\ValidatorSchemaInterface;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as V;

class CitaValidator extends Validator implements ValidatorSchemaInterface
{
    public static function validate(array $data, ?string $type = null): void
    {
        $validator = match ($type) {
            "post" => self::post(),
            "put" => self::put(),
            default => null
        };
        if ($validator) {
            parent::execute($validator, $data);
        }
    }

    private static function post(): Validatable
    {
        return V::key("cita", V::stringType())
            ->key("pagina", V::intVal(), false)
            ->key("comentari", V::stringType(), false)
            ->key("privat", V::boolVal(), false);
    }
    private static function put(): Validatable
    {
        return V::key("pagina", V::intVal(), false)
            ->key("cita", V::stringType(), false)
            ->key("comentari", V::stringType())
            ->key("privat", V::boolVal(), false);
    }
}
