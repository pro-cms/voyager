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

    public string $table = '';
    protected mixed $slug = null;
    protected mixed $name_singular = null;
    protected mixed $name_plural = null;
    public string $icon = 'bread';
    public mixed $model = null;
    public mixed $controller = null;
    public mixed $policy = null;
    public mixed $global_search_field = null;
    public mixed $order_field = null;
    public Collection $layouts;
    public Collection $relationships;

    protected mixed $model_class = null;

    public function __construct(mixed $json)
    {
        $this->layouts = collect();
        $this->relationships = collect();

        collect($json)->each(function ($value, $key) {
            if ($key == 'layouts') {
                foreach ($value as $layout) {
                    $layout = new Layout($layout, $this);
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
        $traits = class_uses($this->getModel());
        if ($traits !== false) {
            return in_array(Translatable::class, $traits);
        }
        
        return false;
    }

    public function usesSoftDeletes(): bool
    {
        $traits = class_uses($this->getModel());
        if ($traits !== false) {
            return in_array(SoftDeletes::class, $traits);
        }

        return false;
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
