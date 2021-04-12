<?php

namespace Areago\UniqueTranslationConstraint\Exceptions;

use RuntimeException;

class QueryException extends RuntimeException
{
    public string $field;

    public array $entries;

    public static function duplicatedTranlation(
        string $field,
        array $entries,
    ): self {
        $e = new static(
            sprintf("[SQL 1062] Duplicated entries [%s] for field '%s'.", collect($entries)->map(fn($e) => "'{$e}'")->join(', '), $field),
            1062);
        $e->field = $field;
        $e->entries = $entries;

        return $e;
    }

    public function entries(): array
    {
        return $this->entries;
    }

    public function field(): string
    {
        return $this->field;
    }
}