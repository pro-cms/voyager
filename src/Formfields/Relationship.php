<?php

namespace Voyager\Admin\Formfields;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use Voyager\Admin\Classes\Formfield;

class Relationship extends Formfield
{
    public bool $notInLists = true;
    public bool $noColumns = true;
    public bool $notTranslatable = true;
    public bool $noComputedProps = true;
    public bool $noRelationshipProps = true;
    public bool $noRelationshipPivots = true;
    public bool $notAsSetting = true;

    public function type(): string
    {
        return 'relationship';
    }

    public function name(): string|array|null
    {
        return __('voyager::formfields.relationship.name');
    }

    public function add(): mixed
    {
        $relationship = $this->getRelationship();

        if ($relationship) {
            if ($relationship['multiple'] === true) {
                return [];
            }
        }
        
        return null;
    }

    public function stored(mixed $model, mixed $value): void
    {
        $relationship = $this->getRelationship();
        $method = $model->{$relationship['method']}();

        switch ($relationship['fqcn']) {
            case BelongsTo::class:
                $method->associate($value)->save();
                break;
            case BelongsToMany::class:
                $method->sync($value);
                break;
            case HasOne::class: case HasMany::class:
                $foreignKey = $method->getForeignKeyName();
                $localKey = $method->getRelated()->getKeyName();
                if (!is_array($value) && $value !== null) {
                    $value = [$value];
                } else if ($value === null) {
                    $value = [];
                }

                // Detach old (which have been removed)
                $method->getRelated()->where($foreignKey, $model->getKey())->whereNotIn($localKey, $value)->each(function ($old) use ($foreignKey) {
                    $old->{$foreignKey} = null;
                    $old->save();
                });

                // Attach new
                $method->getRelated()->whereIn($localKey, $value)->each(function ($new) use ($foreignKey, $model) {
                    $new->{$foreignKey} = $model->getKey();
                    $new->save();
                });

                break;
            default:
                
                break;
        }
    }

    public function edit(mixed $value): mixed
    {
        return $this->add();
    }

    public function updated(mixed $model, mixed $value): void
    {
        $this->stored($model, $value);
    }

    protected function getRelationship(): mixed
    {
        return $this->bread?->relationships?->where('method', $this->column->column)->first() ?? null;
    }
}
