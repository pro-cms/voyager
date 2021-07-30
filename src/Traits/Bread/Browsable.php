<?php

namespace Voyager\Admin\Traits\Bread;

use DB;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Voyager\Admin\Classes\Bread;
use Voyager\Admin\Classes\Layout;
use Voyager\Admin\Classes\Formfield;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;

/**
 * @property bool $uses_soft_deletes
 */
trait Browsable
{
    public function loadSoftDeletesQuery(Bread $bread, Layout $layout, string $softdeleted, mixed $query): mixed
    {
        $this->uses_soft_deletes = $bread->usesSoftDeletes();
        if (!isset($layout->options->soft_deletes) || !$layout->options->soft_deletes || !$this->uses_soft_deletes) {
            $this->uses_soft_deletes = false;

            return $query;
        }
        if ($softdeleted == 'show') {
            $query = $query->withTrashed();
        } elseif ($softdeleted == 'only') {
            $query = $query->onlyTrashed();
        }

        return $query;
    }

    public function globalSearchQuery(string|null $global, Layout $layout, string $locale, mixed $query): mixed
    {
        if (!empty($global)) {
            $query = $query->where(function ($query) use ($global, $layout, $locale) {
                $layout->searchableFormfields()->each(function ($formfield) use (&$query, $global, $locale) {
                    $query = $this->queryColumn($query, $formfield, $global, $locale, true);
                });
            });
        }

        return $query;
    }

    public function columnSearchQuery(array $filters, Layout $layout, mixed $query, string $locale, array &$warnings): mixed
    {
        collect(array_filter($filters))->each(function ($filter, $column) use ($layout, &$query, $locale, &$warnings) {
            $formfield = $layout->getFormfieldByColumn($column);
            if (!$formfield) {
                $warnings[] = __('voyager::bread.column_does_not_exist_search', ['column' => $column]);

                return;
            }

            $query = $this->queryColumn($query, $formfield, $filter, $locale);
        });

        return $query;
    }

    public function applyCustomFilter(Bread $bread, Layout $layout, mixed $filter, mixed $query): mixed
    {
        if (!is_null($filter) && is_array($filter)) {
            // Validate filter exists in layout
            if (collect($layout->options->filters)->where('column', $filter['column'])->where('operator', $filter['operator'])->where('value', $filter['value'])->count() > 0) {
                if ($filter['column']) {
                    $query = $query->where($filter['column'], $filter['operator'], $filter['value']);
                }
            }
        }

        return $query;
    }

    public function applyCustomScope(Bread $bread, Layout $layout, mixed $filter, mixed $query, array &$warnings): mixed
    {
        if (!is_null($filter) && is_array($filter)) {
            // Validate filter exists in layout
            if (collect($layout->options->filters)->where('column', $filter['column'])->where('operator', $filter['operator'])->where('value', $filter['value'])->count() > 0) {
                if ($filter['column'] === null) {
                    if (method_exists($query, $filter['value'])) {
                        $query = $query->{$filter['value']}();
                    } else {
                        $warnings[] = __('voyager::bread.scope_does_not_exist', ['scope' => $filter['value']]);
                    }
                }
            }
        }

        return $query;
    }

    public function orderQuery(Layout $layout, mixed $direction, mixed $order, mixed $query, string $locale, array &$warnings): mixed
    {
        if (!empty($direction) && !empty($order)) {
            $formfield = $layout->getFormfieldByColumn($order);
            if ($formfield && $formfield->column->type == 'column') {
                if ($layout->getFormfieldByColumn($order)->translatable ?? false) {
                    $query = $query->orderBy(DB::raw('lower('.$order.'->"$.'.$locale.'")'), $direction);
                } else {
                    $query = $query->orderBy($order, $direction);
                }
            } elseif ($formfield && $formfield->column->type == 'relationship') {
                $warnings[] = __('voyager::bread.can_not_order_by_relationship', ['column' => $formfield->column->column]);
            } else {
                $warnings[] =  __('voyager::bread.column_does_not_exist_order', ['column' => $order]);
            }
        }

        return $query;
    }

    public function eagerLoadRelationships(Layout $layout, mixed $query, array &$warnings): mixed
    {
        /*$instance = $query->getModel()->newInstance();
        $layout->getFormfieldsByColumnType('relationship')->pluck('column.column')->each(function ($relationship) use (&$query, &$warnings, $instance) {
            list($method) = explode('.', $relationship);

            if (method_exists($instance, $method)) {
                $query = $query->with($method);
            } else {
                $warnings[] = __('voyager::bread.relationship_does_not_exist', ['relationship' => $relationship]);
            }
        });*/

        return $query;
    }

    public function transformResults(Layout $layout, bool $translatable, mixed $query, string|null $global, mixed $filters): mixed
    {
        return $query->transform(function ($item) use ($translatable, $layout) {
            $item->primary_key = $item->getKey();
            if ($translatable) {
                $item->dontTranslate();
            }
            // Add soft-deleted property
            $item->is_soft_deleted = $this->uses_soft_deletes ? $item->trashed() : false;

            $layout->formfields->each(function ($formfield) use (&$item) {
                $column = $formfield->column->column;
                if ($formfield->column->type == 'relationship') {
                    $method = $prop = null;
                    $computed = false;
                    if (Str::contains($column, '.computed.')) {
                        list($method, $notneeded, $prop) = explode('.', $column);
                        $computed = true;
                    } else {
                        list($method, $prop) = explode('.', $column);
                    }
                    
                    if ($computed) {
                        $item->{$column} = 'computed :)';
                        $item->{$column} = $item->{$method}->transform(function ($value) use ($formfield, $prop) {
                            if ($formfield->translatable ?? false) {
                                return VoyagerFacade::getJson($value->{$prop});
                            } else {
                                return $value->{$prop};
                            }
                        });
                    } else {
                        $item->{$column} = $item->{$method}()->pluck($prop)->transform(function ($value) use ($formfield) {
                            if ($formfield->translatable ?? false) {
                                return VoyagerFacade::getJson($value);
                            } else {
                                return $value;
                            }
                        });
                    }
                } elseif ($formfield->translatable ?? false) {
                    $value = $item->{$column};
                    if (is_string($value)) {
                        $value = json_decode($value) ?? [];
                    } elseif (empty($value)) {
                        $value = [];
                    }
                    foreach ($value as $locale => $content) {
                        $value->{$locale} = $formfield->browse($content);
                    }

                    $item->{$column} = $value;
                } else {
                    $item->{$column} = $formfield->browse($item->{$column});
                }
            });

            return $item;
        });
    }

    private function queryColumn(mixed $query, Formfield $formfield, mixed $filter, string $locale, bool $global = false): mixed
    {
        $filter = '%'.strtolower($filter).'%';
        $translatable = $formfield->translatable ?? false;
        $column = $formfield->column->column;

        if (method_exists($formfield, 'query')) {
            return $formfield->query($query, $filter, ($translatable ? $locale : null), $global);
        }

        if ($formfield->column->type == 'column' && $translatable) {
            $query = $query->{$global ? 'orWhereRaw' : 'whereRaw'}('LOWER(`'.$column.'->"$.'.$locale.'`) LIKE ?', [$filter]);
        } elseif ($formfield->column->type == 'column') {
            $query = $query->{$global ? 'orWhereRaw' : 'whereRaw'}('LOWER(`'.$column.'`) LIKE ?', [$filter]);
        } elseif ($formfield->column->type == 'relationship') {
            if ($global) {
                // TODO: We could skip global-searching relationships here
                // return $query;
            }
            list($name, $column) = explode('.', $formfield->column->column);

            $query = $query->{$global ? 'orWhereHas' : 'whereHas'}($name, function ($q) use ($column, $filter, $translatable, $locale) {
                if ($translatable) {
                    $q->whereRaw('LOWER(`'.$column.'->$.'.$locale.'`) LIKE ?', [$filter]);
                } else {
                    $q->whereRaw('LOWER(`'.$column.'`) LIKE ?', [$filter]);
                }
            });
        }

        return $query;
    }
}
