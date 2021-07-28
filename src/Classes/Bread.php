<?php

namespace Voyager\Admin\Classes;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Voyager\Admin\Traits\Translatable;

/**
 * @property string $slug
 * @property string $name_singular
 * @property string $name_plural
 */

class Bread implements \JsonSerializable
{
    use Translatable;

    private array $translatable = ['slug', 'name_singular', 'name_plural'];

    public string $table;
    protected mixed $slug;
    protected mixed $name_singular;
    protected mixed $name_plural;
    public string $icon = 'bread';
    public mixed $model;
    public mixed $controller;
    public mixed $policy;
    public mixed $global_search_field;
    public mixed $order_field;
    public Collection $layouts;

    protected mixed $model_class = null;

    public function __construct(mixed $json)
    {
        $this->layouts = collect();

        collect($json)->each(function ($value, $key) {
            if ($key == 'layouts') {
                foreach ($value as $layout) {
                    $layout = new Layout($layout);
                    $this->layouts->push($layout);
                }
            } else {
                $this->{$key} = $value;
            }
        });
    }

    public function getModel(): mixed
    {
        if (!$this->model_class) {
            $this->model_class = app($this->model);
        }

        return $this->model_class;
    }

    public function usesTranslatableTrait(): bool
    {
        return in_array(Translatable::class, class_uses($this->getModel()));
    }

    public function usesSoftDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses($this->getModel()));
    }

    public function jsonSerialize(): array
    {
        return [
            'table'               => $this->table,
            'slug'                => $this->slug,
            'name_singular'       => $this->name_singular,
            'name_plural'         => $this->name_plural,
            'icon'                => $this->icon,
            'model'               => $this->model,
            'controller'          => $this->controller,
            'policy'              => $this->policy,
            'global_search_field' => $this->global_search_field,
            'order_field'         => $this->order_field,
            'layouts'             => $this->layouts,
        ];
    }
}
