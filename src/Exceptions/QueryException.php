<?php

namespace Areago\UniqueTranslationConstraint\Exceptions;

use RuntimeException;

class QueryException extends RuntimeException
{
    public array $fields;

    public array $entries;

    public static function duplicatedTranlation(
        array $fields,
        array $entries,
    ): self {
        $e = new static(sprintf("[SQL 1062] Duplicated entries [%s] for fields {'%s'}.",
            collect($entries)->map(fn($e) => json_decode($e))->toJson(\JSON_UNESCAPED_UNICODE),
            collect($fields)->map(fn($f) => "'{$f}'")->join(', ')),
            1062);
        $e->fields = $fields;
        $e->entries = $entries;

        return $e;
    }

    public function entries(): array
    {
        return $this->entries;
    }

    public function fields(): array
    {
        return $this->fields;
    }
}
