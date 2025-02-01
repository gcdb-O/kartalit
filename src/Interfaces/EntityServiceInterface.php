<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

interface EntityServiceInterface
{
    public function getAll(): array;
    public function getById(int $id): ?object;
}
