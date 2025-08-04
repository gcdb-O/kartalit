<?php

declare(strict_types=1);


namespace Kartalit\Schemas\Validation;

use Kartalit\Interfaces\ValidatorSchemaInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as V;

class PaginationValidator extends Validator implements ValidatorSchemaInterface
{
    public static function validate(Request &$request, ?string $type = null): void
    {
        $validator = self::queryPagValidator();
        $data = $request->getQueryParams();
        $newData = parent::execute($validator, $data, true);
        $request = $request->withQueryParams($newData);
    }
    public static function queryPagValidator(): Validatable
    {
        return V::key("pagina", V::intVal()->min(1), false);
    }
}
