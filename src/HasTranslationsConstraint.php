<?php

namespace Areago\UniqueTranslationConstraint;

use JetBrains\PhpStorm\Pure;

/**
 * @property array $translatableUniqueConstraints
 */
trait HasTranslationsConstraint
{
    /**
     * Hook model events.
     *
     * @noinspection PhpUndefinedMethodInspection
     */
    public static function bootHasTranslationsConstraint(): void
    {
        static::observe(UniqueConstraintObserver::class);
    }

    #[Pure]
    public function getTranslatableUniqueConstraintsAttributes(): array
    {
        return $this->translatableUniqueConstraints ?? [];
    }
}
