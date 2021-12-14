<?php

namespace Voyager\Admin\Traits\Facade;

use Illuminate\Support\Collection;

use Voyager\Admin\Classes\Widget;
use Voyager\Admin\Contracts\Plugins\Features\Filter\Widgets as WidgetFilter;

trait Widgets
{
    protected Collection $widgets;

    /**
     * Gets all widgets from installed and enabled plugins filtered by plugins.
     */
    public function getWidgets(): Collection
    {
        $widgets = $this->widgets->filter(function ($widget) {
            if (($widget->permission['ability'] ?? null) === null) {
                return true;
            }

            return $this->authorize(
                $this->auth()->user(),
                $widget->permission['ability'] ?? null,
                $widget->permission['arguments'] ?? []
            );
        });
        $this->pluginmanager->getAllPlugins()->each(function ($plugin) use (&$widgets) {
            if ($plugin instanceof WidgetFilter) {
                $widgets = $plugin->filterWidgets($widgets);
            }
        });

        return $widgets;
    }

    /**
     * Add one or many widgets to the dashboard.
     */
    public function addWidgets(): void
    {
        $this->widgets = $this->widgets->merge(collect(func_get_args())->filter(function ($widget) {
            return $widget instanceof Widget;
        }));
    }
}