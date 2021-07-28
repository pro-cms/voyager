<?php

namespace Voyager\Admin\Formfields;

use Voyager\Admin\Classes\Formfield;

class Radio extends Formfield
{
    public function type(): string
    {
        return 'radio';
    }

    public function name(): string
    {
        return __('voyager::formfields.radio.name');
    }

    public function add(): mixed
    {
        return null;
    }
}
