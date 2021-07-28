<?php

namespace Voyager\Admin\Formfields;

use Voyager\Admin\Classes\Formfield;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;

class Tags extends Formfield
{
    public bool $browseArray = true;

    public function type(): string
    {
        return 'tags';
    }

    public function name(): string
    {
        return __('voyager::formfields.tags.name');
    }

    public function add(): mixed
    {
        return [];
    }

    public function browse(mixed $value): mixed
    {
        if (!is_array($value)) {
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
        return json_encode($value);
    }

    public function update(mixed $model, mixed $value, mixed $old): mixed
    {
        return $this->store($value);
    }
}
