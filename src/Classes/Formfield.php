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
    public Column $column;
    public ?int $tab = null;
    public ?string $link_to = null;
    public bool $translatable = false;
    protected ?Bread $bread = null;

    public function __construct(?object $json = null)
    {
        if ($json) {
            foreach ((array)$json as $key => $value) {
                if ($key == 'column') {
                    $value = (object)$value;
                    $this->column = new Column($value->type ?? '', $value->column ?? '');
                } else if (!in_array($key, $this->dontStore)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    /**
     * Transform incoming data used when browsing.
     */
    public function browse(mixed $value): mixed
    {
        return $value;
    }

    /**
     * Transform incoming data used when reading.
     */
    public function read(mixed $value): mixed
    {
        return $value;
    }

    /**
     * Transform incoming data used when editing.
     */
    public function edit(mixed $value): mixed
    {
        return $value;
    }

    /**
     * Transform incoming data before updating.
     */
    public function update(mixed $model, mixed $value, mixed $old): mixed
    {
        return $value;
    }

    /**
     * Transform incoming data after updating.
     */
    public function updated(mixed $model, mixed $value): void {}

    public function add(): mixed
    {
        return '';
    }

    /**
     * Transform incoming data before storing.
     */
    public function store(mixed $value): mixed
    {
        return $value;
    }

    /**
     * Transform incoming data after storing.
     */
    public function stored(mixed $model, mixed $value): void {}

    /**
     * Set the BREAD this formfields belongs to. Used internally.
     */
    public function setBread(Bread $bread): void {
        $this->bread = $bread;
    }

    public function jsonSerialize()
    {
        return (object) collect((array) $this)->filter(function ($value, $key) {
            return !in_array($key, $this->dontStore);
        })->toArray();
        
    }
}