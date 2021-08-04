<?php

namespace Voyager\Admin\Classes;

use Illuminate\Support\Collection;
use Voyager\Admin\Manager\Breads as BreadManager;
use Voyager\Admin\Classes\Bread;
use Voyager\Admin\Classes\Formfield;

class Layout implements \JsonSerializable
{
    public string $name;
    public string $type = 'list';
    public \stdClass $options;
    public Collection $formfields;
    protected BreadManager $breadmanager;

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
