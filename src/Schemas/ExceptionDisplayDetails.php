<?php

declare(strict_types=1);

namespace Kartalit\Schemas;

use Throwable;

class ExceptionDisplayDetails
{
    public string $type;
    public int $code;
    public string $message;
    public string $file;
    public int $line;
    public array $trace;
    public function __construct(Throwable $throwable)
    {
        $this->type = get_class($throwable);
        $this->code = $throwable->getCode();
        $this->message = $throwable->getMessage();
        $this->file = $throwable->getFile();
        $this->line = $throwable->getLine();
        $this->trace = $throwable->getTrace();
    }
    public static function toArray(Throwable $throwable): array
    {
        return (array) (new self($throwable));
    }
}
