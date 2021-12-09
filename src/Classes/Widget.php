<?php

namespace Voyager\Admin\Classes;

class Widget
{
    public int $width;
    public string $icon;
    public array $parameters = [];
    public array $permission = [];

    /**
     * Create a new widget.
     */
    public function __construct(
        public string $component,
        public string $title
        )
    { }

    /**
     * Sets the width of the widget.
     */
    public function width(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Sets the icon of the widget.
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Sets the parameter that will be passed to the widget component.
     */
    public function parameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Sets the permission needed to show this widget.
     */
    public function permission(string $ability, array $arguments = []): self
    {
        $this->permission = [
            'ability'   => $ability,
            'arguments' => $arguments,
        ];

        return $this;
    }
}
