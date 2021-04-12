<?php

namespace Areago\UniqueTranslationConstraint;

use Areago\UniqueTranslationConstraint\Asserts\Assert;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;
use LogicException;
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

    public function hasTranslation(string | array $fields): bool
    {
        $translations = $this->getTranslations(collect($fields));

        try {
            Assert::shouldNotBeEmpty($translations, 'No translatable fields found in provided constraints.');
        } catch (Exception $e) {
            throw new LogicException($e->getMessage());
        }

        // Self Model Query
        $query = $this->model->query()->take(1);

        // Skip on updating self model by primary key
        $primary = $this->model->getAttributeValue($this->model->getKeyName());
        if (null !== $primary) {
            $query->whereKeyNot($primary);
        }

        // Skip on updating self model by additional constraints
        foreach (collect($fields)->diff($this->model->getTranslatableAttributes()) as $attribute) {
            $query->where($attribute, '!=', $this->model->getAttribute($attribute));
        }

        $query->where(function ($query) use ($translations) {
            foreach ($translations as $key => $translation) {
                foreach ($translation as $locale => $value) {
                    $query->orWhereRaw("{$key}->>'$.{$locale}' = ?", [$value]);
                }
            }
        });

        // dd($query->getQuery()->toSql());

        return null !== $query->first();
    }

    public function getTranslations(Collection $fields): Collection
    {
        $translations = [];

        $translatable = collect($fields)->intersect($this->model->getTranslatableAttributes());
        foreach ($translatable as $attribute) {
            $translations[$attribute] = $this->model->getTranslations($attribute);
        }

        return collect($translations);
    }
}
