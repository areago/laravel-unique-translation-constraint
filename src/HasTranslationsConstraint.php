<?php

namespace Areago\UniqueTranslationConstraint;

use JetBrains\PhpStorm\Pure;

/**
 * @property string[] $translatableUniqueConstraints
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
        return is_array($this->translatableUniqueConstraints)
            ? $this->translatableUniqueConstraints
            : [];
    }
}