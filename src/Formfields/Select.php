<?php

namespace Voyager\Admin\Formfields;

use Voyager\Admin\Classes\Formfield;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;

class Select extends Formfield
{
    public bool $browseArray = true;

    public function type(): string
    {
        return 'select';
    }

    public function name(): string|array|null
    {
        return __('voyager::formfields.select.name');
    }

    public function add(): mixed
    {
        if ($this->options->multiple ?? false) {
            return [];
        }

        return null;
    }

    public function browse(mixed $value): mixed
    {
        if (($this->options->multiple ?? false) && !is_array($value)) {
            return VoyagerFacade::getJson($value, []);
        }

        return $value;
    }

    public function edit(mixed $value): mixed
    {
        return $this->browse($value);
    }

    public function store(mixed $value): mixed
    {
        if ($this->options->multiple ?? false) {
            return json_encode($value);
        }

        return $value;
    }

    public function update(mixed $model, mixed $value, mixed $old): mixed
    {
        return $this->store($value);
    }
}
