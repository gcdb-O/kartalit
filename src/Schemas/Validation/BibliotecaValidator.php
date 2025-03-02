<?php

declare(strict_types=1);

namespace Kartalit\Schemas\Validation;

use Kartalit\Interfaces\ValidatorSchemaInterface;
use Kartalit\Schemas\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;

class BibliotecaValidator extends Validator implements ValidatorSchemaInterface
{
    public static function validate(Request &$request, ?string $type = null): void
    {
        $validator = V::key("pagina", V::intVal()->min(1), false);
        $data = $request->getQueryParams();
        $newData = parent::execute($validator, $data, true);
        $request = $request->withQueryParams($newData);
    }
}
