<?php

namespace Voyager\Admin\Formfields;

use Voyager\Admin\Classes\Formfield;

class Password extends Formfield
{
    public bool $notTranslatable = true;

    public function type(): string
    {
        return 'password';
    }

    public function name(): string|array|null
    {
        return __('voyager::formfields.password.name');
    }

    public function edit(mixed $value): mixed
    {
        return null;
    }

    public function update(mixed $model, mixed $value, mixed $old): mixed
    {
        if (empty($value)) {
            return $old;
        }

        return $this->store($value);
    }

    public function store(mixed $value): mixed
    {
        return bcrypt($value);
    }
}
