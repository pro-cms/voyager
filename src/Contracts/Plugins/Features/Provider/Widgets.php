<?php

namespace Voyager\Admin\Contracts\Plugins\Features\Provider;

use Illuminate\Support\Collection;
use Voyager\Admin\Classes\Widget;

/**
 * An interface for plugins that want to add widgets to the dashboard.
 */
interface Widgets
{
    /**
     * Registers the plugin's frontend routes.
     *
     * @return Collection<Widget>
     */
    public function provideWidgets(): Collection;
}