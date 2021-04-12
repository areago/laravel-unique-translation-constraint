<?php

namespace Areago\UniqueTranslationConstraint;

use Areago\UniqueTranslationConstraint\Exceptions\QueryException;
use Illuminate\Database\Eloquent\Model;

class UniqueConstraintObserver
{
    private UniqueConstraintService $service;

    public function saving(Model $model): void
    {
        $this->service = UniqueConstraintService::create($model);

        $this->assertUniqueTranslations();
    }

    private function assertUniqueTranslations(): void
    {
        $attributes = $this->service->model->getTranslatableUniqueConstraintsAttributes();

        foreach ($attributes as $attribute) {
            if ($this->service->hasTranslation($attribute)) {
                throw QueryException::duplicatedTranlation($attribute, $this->service->model->getTranslations($attribute));
            }
        }
    }
}