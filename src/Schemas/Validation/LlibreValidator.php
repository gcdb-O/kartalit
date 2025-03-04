<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Interfaces\ValidatorSchemaInterface;
use Kartalit\Schemas\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as V;

class LlibreValidator extends Validator implements ValidatorSchemaInterface
{
    public static function validate(Request &$request, ?string $type = null): void
    {
        switch ($type) {
            case "getAllQuery":
                $validator = self::getAllQuery();
                $data = $request->getQueryParams();
                $newData = parent::execute($validator, $data, true);
                $request = $request->withQueryParams($newData);
                break;
        }
    }

    private static function getAllQuery(): Validatable
    {
        return V::key("pagina", V::intVal()->min(1), false);
    }
}
