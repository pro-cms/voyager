<?php

namespace Voyager\Admin\Classes;

use Illuminate\Support\Collection;
use Voyager\Admin\Manager\Breads as BreadManager;
use Voyager\Admin\Classes\Formfield;

class Layout implements \JsonSerializable
{
    public string $name;
    public string $type = 'list';
    public \stdClass $options;
    public Collection $formfields;
    protected BreadManager $breadmanager;

    public function __construct(mixed $json)
    {
        $this->breadmanager = resolve(BreadManager::class);
        $this->formfields = collect();
        collect($json)->each(function ($value, $key) {
            if ($key == 'formfields') {
                foreach ($value as $f) {
                    $formfield = $this->breadmanager->getFormfield($f->type);
                    if (!$formfield) {
                        throw new \Exception('Formfield with type "'.$f->type.'" does not exist!');
                    }
                    $formfield = clone $formfield;
                    foreach ($f as $key => $prop) {
                        $formfield->{$key} = $prop;
                    }
                    $this->formfields->push($formfield);
                }
            } else {
                $this->{$key} = $value;
            }
        });
    }

    public function searchableFormfields(): Collection
    {
        return $this->formfields->where('searchable');
    }

    public function getFormfieldByColumn(string $column): Formfield|null
    {
        return $this->formfields->where('column.column', $column)->first();
    }

    public function getFormfieldsByColumnType(string $type): Collection
    {
        return $this->formfields->where('column.type', $type);
    }

    public function jsonSerialize()
    {
        return [
            'name'       => $this->name,
            'type'       => $this->type,
            'options'    => $this->options,
            'formfields' => $this->formfields,
        ];
    }
}
