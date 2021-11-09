<?php

namespace Voyager\Admin\Classes;

use Illuminate\Support\Collection;

class MenuItem implements \JsonSerializable
{
    public array $permission = [];
    public string $url = '';
    public string $route = '';
    public array $route_params = [];
    public string $href = '';
    public bool $divider = false;
    public bool $exact = false;
    public ?string $badge_value = null;
    public ?string $badge_color = null;
    public Collection $children;

    /**
     * Create a new menu item.
     */
    public function __construct(
        public array|string|null $title = '',
        public string $icon = '',
        public bool $main = false
    )
    {
        $this->children = collect();
    }

    /**
     * Set the permission needed to display this menu item.
     */
    public function permission(string $ability, array $arguments = []): self
    {
        $this->permission = [
            'ability'   => $ability,
            'arguments' => $arguments,
        ];

        return $this;
    }

    /**
     * Set the route name used to resolve the URL for this menu item.
     */
    public function route(string $route, array $params = []): self
    {
        $this->route = $route;
        $this->route_params = $params;

        return $this;
    }

    /**
     * Set the URL for this menu item.
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Make this menu item a visual divider.
     */
    public function divider(): self
    {
        $this->divider = true;

        return $this;
    }

    /**
     * Set that the URL of the menu item and the requested URL must be exactly the same to consider this item as "active".
     */
    public function exact(): self
    {
        $this->exact = true;

        return $this;
    }

    /**
     * Add children to this menu item.
     */
    public function addChildren(): self
    {
        $this->children = $this->children->merge(func_get_args());

        return $this;
    }

    /**
     * Display a badge on this menu item.
     */
    public function badge(?string $color = 'green', ?string $value = null): self
    {
        $this->badge_color = $color;
        $this->badge_value = $value;

        return $this;
    }

    /**
     * Resolves the URL for this menu item based on the route or the URL.
     */
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
