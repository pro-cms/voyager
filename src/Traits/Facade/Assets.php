<?php

namespace Voyager\Admin\Traits\Facade;

use Illuminate\Support\Collection;

trait Assets
{
    protected Collection $assets;

    /**
     * Add a javascript file.
     */
    public function addJavascript(string $path): void
    {
        $this->assets->push([
            'type'  => 'js',
            'path'  => $path,
        ]);
    }

    /**
     * Add a CSS file.
     */
    public function addCSS(string $path): void
    {
        $this->assets->push([
            'type'  => 'css',
            'path'  => $path,
        ]);
    }

    /**
     * Get all custom assets.
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }
}