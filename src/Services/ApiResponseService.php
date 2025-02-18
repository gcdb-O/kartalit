<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Enums\HttpStatusCode;
use Kartalit\Schemas\ApiResponse;
use Psr\Http\Message\ResponseInterface as Response;

readonly class ApiResponseService
{
    public function toJson(Response $response, ApiResponse $apiResponse, HttpStatusCode|int|null $status = null): Response
    {
        $response->getBody()->write(json_encode($apiResponse(), JSON_PRETTY_PRINT));
        if ($status !== null && $status !== 0) {
            $statusCode = $status instanceof HttpStatusCode ? $status->value : $status;
            if ($statusCode !== null) {
                $response = $response->withStatus($statusCode);
            }
        }
        return $response;
    }
}
