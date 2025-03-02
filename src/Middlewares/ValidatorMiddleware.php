<?php

declare(strict_types=1);

namespace Kartalit\Middlewares;

use InvalidArgumentException;
use Kartalit\Interfaces\ValidatorSchemaInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(private string $validatorSchema, private ?string $type = null)
    {
        if (!is_subclass_of($this->validatorSchema, ValidatorSchemaInterface::class)) {
            throw new InvalidArgumentException();
        }
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->validatorSchema::validate($request, $this->type);
        return $handler->handle($request);
    }
}
