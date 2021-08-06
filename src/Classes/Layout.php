<?php

namespace Voyager\Admin\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Voyager\Admin\Manager\Breads as BreadManager;
use Voyager\Admin\Classes\Bread;
use Voyager\Admin\Classes\Formfield;

class Layout implements \JsonSerializable
{
    public string $name;
    public string $type = 'list';
    public \stdClass $options;
    public Collection $formfields;
    public array $tabs = [];
    public ?string $uuid = null;
    protected BreadManager $breadmanager;

    /**
     * Construct a new layout based on JSON input.
     */
    public function __construct(mixed $json, Bread $bread)
    {
        $this->breadmanager = resolve(BreadManager::class);
        $this->formfields = collect();
        collect($json)->each(function ($value, $key) use ($bread) {
            if ($key == 'formfields') {
                foreach ($value as $f) {
                    $f = (object) $f;
                    $class = $this->breadmanager->getFormfieldClass($f->type);
                    if (!$class) {
                        throw new \Exception('Formfield with type "'.$f->type.'" does not exist!');
                    }
                    
                    $formfield = new $class($f);
                    $formfield->setBread($bread);
                    $this->formfields->push($formfield);
                }
            } elseif ($key == 'options') {
                $this->{$key} = (object) $value;
            } else {
                $this->{$key} = $value;
            }
        });

        if ($this->uuid === null) {
            $this->uuid = (string) Str::uuid();
        }
    }

    /**
     * Get all searchable formfields.
     */
    public function searchableFormfields(): Collection
    {
        return $this->formfields->where('searchable');
    }

    /**
     * Get a formfield based on the column.
     */
    public function getFormfieldByColumn(string $column): ?Formfield
    {
        return $this->formfields->where('column.column', $column)->first();
    }

    /**
     * Get all formfields based on the column-type.
     */
    public function getFormfieldsByColumnType(string $type): Collection
    {
        return $this->formfields->where('column.type', $type);
    }

    public function jsonSerialize()
    {
        return [
            'name'       => $this->name,
            'uuid'       => $this->uuid,
            'type'       => $this->type,
            'tabs'       => $this->tabs,
            'options'    => $this->options,
            'formfields' => $this->formfields,
        ];
    }
}
