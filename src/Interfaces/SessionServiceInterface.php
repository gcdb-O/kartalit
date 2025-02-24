<?php

declare(strict_types=1);

namespace Kartalit\Interfaces;

interface SessionServiceInterface
{
    public function startSession(): void;
    public function endSession(): void;
}
