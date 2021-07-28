<?php

namespace Voyager\Admin\Formfields;

use Illuminate\Support\Str;
use Voyager\Admin\Classes\Formfield;

class Text extends Formfield
{
    public function type(): string
    {
        return 'text';
    }

    public function name(): string|array|null
    {
        return __('voyager::formfields.text.name');
    }

    public function browse(mixed $input): mixed
    {
        return Str::limit(strip_tags($input), $this->options->display_length ?? 150);
    }

    public function add(): mixed
    {
        return $this->options->default_value ?? '';
    }
}
