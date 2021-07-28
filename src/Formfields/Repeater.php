<?php

namespace Voyager\Admin\Formfields;

use Voyager\Admin\Classes\Formfield;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;

class Repeater extends Formfield
{
    public bool $browseArray = true;

    public function type(): string
    {
        return 'repeater';
    }

    public function name(): string
    {
        return __('voyager::formfields.repeater.name');
    }

    public function add(): mixed
    {
        return [];
    }

    public function browse(mixed $value): mixed
    {
        if (!is_array($value)) {
            $value = VoyagerFacade::getJson($value, []);
        }

        return $value;
    }

    public function read(mixed $value): mixed
    {
        return $this->edit($value);
    }

    public function edit(mixed $value): mixed
    {
        if (!is_array($value)) {
            $value = VoyagerFacade::getJson($value, []);
        }

        if ($this->asArray() && is_array($value)) {
            foreach ($value as $key => $row) {
                $value[$key] = ['null' => $row];
            }
        }

        return $value;
    }

    public function store(mixed $value): mixed
    {
        if ($this->asArray()) {
            foreach ($value as $key => $row) {
                $value[$key] = $row['null'];
            }
        }

        return json_encode($value);
    }

    public function update(mixed $model, mixed $value, mixed $old): mixed
    {
        return $this->store($value);
    }

    private function asArray(): bool
    {
        $withoutKey = false;
        collect($this->options->formfields ?? [])->each(function ($formfield) use (&$withoutKey) {
            if ($formfield->column->column == null || $formfield->column->column == '') {
                $withoutKey = true;
            }
        });

        return ($withoutKey && count($this->options->formfields) == 1);
    }
}
