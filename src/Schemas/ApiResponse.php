<?php

declare(strict_types=1);

namespace Kartalit\Schemas;

use Kartalit\Enums\ApiResponseStatus;
use Throwable;

class ApiResponse
{
    public function __construct(
        private array|PaginatedData $data = [],
        private ?string $message = null,
        private ApiResponseStatus $status = ApiResponseStatus::SUCCESS,
        private ?Throwable $exception = null,
    ) {}
    public function __invoke(): array
    {
        $response = [
            "status" => $this->status->value,
            "message" => $this->message,
            "data" => $this->data instanceof PaginatedData ? $this->data->toArray() : $this->data,
        ];
        if (
            $this->status->value > 399 &&
            $this->status->value !== 404 &&
            $this->data === []
        ) {
            unset($response['data']);
        }
        if ($this->exception !== null) {
            $response["error"] = ExceptionDisplayDetails::toArray($this->exception);
        }
        return $response;
    }
    public function setData(array|PaginatedData $data): void
    {
        $this->data = $data instanceof PaginatedData ? $data->toArray() : $data;
    }
    public function getData(): array
    {
        return $this->data;
    }
    public function setStatus(ApiResponseStatus $status): void
    {
        $this->status = $status;
    }
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
    public function setExcepion(Throwable $excepion): void
    {
        $this->exception = $excepion;
    }
}
