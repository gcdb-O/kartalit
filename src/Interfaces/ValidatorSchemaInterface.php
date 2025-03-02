<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface ValidatorSchemaInterface
{
    public static function validate(ServerRequestInterface &$request): void;
}
