<?php

namespace Voyager\Admin\Formfields;

use Voyager\Admin\Classes\Formfield;

class Toggle extends Formfield
{
    public function type(): string
    {
        return 'toggle';
    }

    public function name(): string|array|null
    {
        return __('voyager::formfields.toggle.name');
    }
}
