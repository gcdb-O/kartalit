<?php

declare(strict_types=1);

namespace Kartalit\Enums;

enum ApiResponseStatus: string
{
    case SUCCESS = "success";
    case FAILURE = "failure";
    case NOT_FOUND = "not_found";
    case ERROR = "error";
}
