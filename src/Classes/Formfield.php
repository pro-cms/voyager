<?php

namespace Voyager\Admin\Classes;

class Formfield
{
    public mixed $options;
    public object $column;
    public bool $translatable = false;

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
}