<?php

declare(strict_types=1);

namespace Kartalit\Schemas;

use Kartalit\Services\PaginatorService;

class PaginatedData
{
    public function __construct(
        private array $results = [],
        private int $total,
        private int $page = 1,
        private ?int $countPage = null
    ) {
        if ($countPage === null) {
            $this->countPage = count($results);
        }
    }
    public static function fromPagination(PaginatorService $paginator, int $page = 1, ?int $countPage = null): self
    {
        return new self($paginator->toArray(), count($paginator), $page, $countPage);
    }
    public function __invoke(): array
    {
        return $this->toArray();
    }
    public function toArray(): array
    {
        return [
            "results" => $this->results,
            "total" => $this->total,
            "page" => $this->page,
            "count_page" => $this->countPage ?: count($this->results),
        ];
    }
    public function addResult(mixed $result): void
    {
        array_push($this->results, $result);
    }
}
