<?php

namespace Areago\UniqueTranslationConstraint;

use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;
use Spatie\Translatable\HasTranslations;

/**
 * @property Model & HasTranslations & HasTranslationsConstraint $model
 */
#[Immutable]
class UniqueConstraintService
{
    protected function __construct(
        public Model $model
    ) {
    }

    #[Pure]
    public static function create(
        Model $model
    ): self {
        return new static($model);
    }

    public function hasTranslation(string $attribute): bool
    {
        $translations = $this->model->getTranslations($attribute);
        if (empty($translations)) {
            return false;
        }

        $query = $this->model->query()->take(1);

        // skip updating self model
        $primary = $this->model->getAttributeValue($this->model->getKeyName());
        if (null !== $primary) {
            $query->whereKeyNot($primary);
        }

        $query->where(function ($query) use ($translations) {
            foreach ($translations as $locale => $value) {
                $query->orWhereRaw("name->>'$.{$locale}' = ?", [$value]);
            }
        });

        return null !== $query->first();
    }
}