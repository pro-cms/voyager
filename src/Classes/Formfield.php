<?php

namespace Voyager\Admin\Classes;

use Voyager\Admin\Classes\Bread;

class Formfield implements \JsonSerializable
{
    // The following properties will not be stored in JSON files
    protected array $dontStore = [
        'notTranslatable',
        'notAsSetting',
        'notInLists',
        'notInViews',
        'browseArray',
        'noColumns',
        'noComputedProps',
        'noRelationships',
        'noRelationshipProps',
        'noRelationshipPivots'
    ];
    public mixed $options;
    public object $column;
    public int|null $tab = null;
    public string|null $link_to = null;
    public bool $translatable = false;
    protected Bread $bread;

    public function browse(mixed $value): mixed
    {
        return $value;
    }

    public function read(mixed $value): mixed
    {
        return $value;
    }

    public function edit(mixed $value): mixed
    {
        return $value;
    }

    public function update(mixed $model, mixed $value, mixed $old): mixed
    {
        return $value;
    }

    public function updated(mixed $model, mixed $value): void {}

    public function add(): mixed
    {
        return '';
    }

    public function store(mixed $value): mixed
    {
        return $value;
    }

    public function stored(mixed $model, mixed $value): void {}

    public function setBread(Bread $bread): void {
        $this->bread = $bread;
    }

    public function __construct(object|null $json = null)
    {
        if ($json) {
            foreach ($json as $key => $value) {
                if ($key == 'column') {
                    $this->{$key} = (object) $value;
                } else if (!in_array($key, $this->dontStore)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function jsonSerialize()
    {
        return (object) collect((array) $this)->filter(function ($value, $key) {
            return !in_array($key, $this->dontStore);
        })->toArray();
        
    }
}