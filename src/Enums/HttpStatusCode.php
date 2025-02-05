<?php

declare(strict_types=1);

namespace Kartalit\Enums;

enum HttpStatusCode: int
{
    case OK = 200;
    case CREATED = 201;
    case NO_CONTENT = 204;
    case REDIRECT_TEMP = 302;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case TEAPOT = 418;
    case SERVER_ERROR = 500;
}
