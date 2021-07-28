<?php

namespace Voyager\Admin\Classes;

use Illuminate\Support\Collection;

class MenuItem implements \JsonSerializable
{
    public array|string|null $title;
    public string $icon;
    public bool $main;
    public array $permission = [];
    public string $url = '';
    public string $route = '';
    public array $route_params = [];
    public string $href = '';
    public bool $divider = false;
    public bool $exact = false;
    public Collection $children;

    public function __construct(array|string|null $title = '', string $icon = '', bool $main = false)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->main = $main;
        $this->children = collect();
    }

    public function permission(string $ability, array $arguments = []): self
    {
        $this->permission = [
            'ability'   => $ability,
            'arguments' => $arguments,
        ];

        return $this;
    }

    public function route(string $route, array $params = []): self
    {
        $this->route = $route;
        $this->route_params = $params;

        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function divider(): self
    {
        $this->divider = true;

        return $this;
    }

    public function exact(): self
    {
        $this->exact = true;

        return $this;
    }

    public function addChildren(): self
    {
        $this->children = $this->children->merge(func_get_args());

        return $this;
    }

    private function resolveUrl(): string
    {
        if ($this->route !== '' && \Route::has($this->route)) {
            return route($this->route, $this->route_params);
        } elseif ($this->url != '') {
            return $this->url;
        }

        return '#';
    }

    public function jsonSerialize()
    {
        return array_merge((array) $this, [
            'href'  => $this->resolveUrl()
        ]);
    }
}
