<?php

namespace Voyager\Admin\Formfields;

use DB;
use Voyager\Admin\Classes\Formfield;

class Number extends Formfield
{
    public function type(): string
    {
        return 'number';
    }

    public function name(): string
    {
        return __('voyager::formfields.number.name');
    }

    public function add(): mixed
    {
        return $this->options->default_value ?? 0;
    }

    public function query(mixed $query, mixed $filter, string|null $locale = null, bool $global = false): mixed
    {
        $from = $filter['from'] ?? ($global ? $filter : 0);
        $to = $filter['to'] ?? null;
        $column = $this->column->column;

        return $query->{$global ? 'orWhere' : 'where'}(function ($q) use ($from, $to, $locale, $column) {
            if (is_null($locale)) {
                $q = $q->where(DB::raw('lower('.$column.')'), '>=', $from);
                if ($to) {
                    return $q->where(DB::raw('lower('.$column.')'), '<=', $to);
                }

                return $q;
            }

            $q = $q->where(DB::raw('lower('.$column.'->"$.'.$locale.'")'), '>=', $from);
            if ($to) {
                return $q->where(DB::raw('lower('.$column.'->"$.'.$locale.'")'), '<=', $to);
            }

            return $q;
        });
    }
}
