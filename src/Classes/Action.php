<?php

namespace Voyager\Admin\Classes;

class Action
{
    public string $method = 'get';
    public bool $download = false;
    public string $file_name = '';
    public bool $bulk = false;
    public array $confirm = [];
    public array $success = [];
    public string $permission = '';
    public mixed $route_callback = null;
    public mixed $callback = null;
    public ?bool $display_deletable = null;
    public bool $reload_after = false;

    /**
     * Create a new action.
     */
    public function __construct(
        public string $title,
        public ?string $icon = null,
        public ?string $buttoncolor = null,
        public ?string $iconcolor = null,
        public ?string $textcolor = null
        )
    { }

    /**
     * Set the method that is used when calling/clicking the action.
     * Can be either get, post, put, patch or delete.
     */
    public function method(string $method): self
    {
        $method = strtolower($method);
        if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            throw new \Exception('Method "'.$method.'" can not be used in an action!');
        }
        $this->method = $method;

        return $this;
    }

    /**
     * Makes the action a download action.
     */
    public function download(string $file_name): self
    {
        $this->download = true;
        $this->file_name = $file_name;

        return $this;
    }

    /**
     * Resolve the route for a BREAD.
     */
    public function route(callable|string $route): self
    {
        $this->route_callback = $route;

        return $this;
    }

    /**
     * Confirm the execution of the action.
     */
    public function confirm(string $message, string $title = null, string $color = 'accent'): self
    {
        $this->confirm = [
            'title'     => $title,
            'message'   => $message,
            'color'     => $color,
        ];

        return $this;
    }

    /**
     * Display a message after executing the action.
     */
    public function success(string $message, string $title = null, string $color = 'accent'): self
    {
        $this->success = [
            'title'     => $title,
            'message'   => $message,
            'color'     => $color,
        ];

        return $this;
    }

    /**
     * Make the action a bulk-action.
     */
    public function bulk(): self
    {
        $this->bulk = true;

        return $this;
    }

    /**
     * Authorize the action based on a permission.
     */
    public function permission(string $ability): self
    {
        $this->permission = $ability;

        return $this;
    }

    /**
     * Sets if this action should be displayed on a BREAD.
     */
    public function displayOnBread(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * This action should be displayed on deleted entries. Will receive the amount of deleted entries when it's a bulk-action (hidden if 0).
     */
    public function displayDeletable(): self
    {
        $this->display_deletable = true;

        return $this;
    }

    /**
     * This action should be displayed on deleted entries. Will receive the amount of deleted entries when it's a bulk-action (hidden if 0).
     */
    public function displayRestorable(): self
    {
        $this->display_deletable = false;

        return $this;
    }

    /**
     * Makes BREAD browse reload once the action finished.
     */
    public function reloadAfter(): self
    {
        $this->reload_after = true;

        return $this;
    }
}
