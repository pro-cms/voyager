<?php

namespace Voyager\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Voyager\Admin\Contracts\Plugins\Features\Provider\SettingsComponent;
use Voyager\Admin\Manager\Plugins as PluginManager;

class PluginsController extends Controller
{
    public function __construct(protected PluginManager $pluginmanager)
    {
        parent::__construct();
    }

    public function index(): InertiaResponse
    {
        return $this->inertiaRender('Plugins', __('voyager::plugins.plugins'), [
            'installed-plugins'     => $this->getInstalledPlugins(),
            'uninstalled-plugins'   => $this->getUninstalledPlugins(),
        ]);
    }

    public function enable(Request $request): string|bool
    {
        $identifier = $request->get('identifier');
        if ($request->get('enable', false)) {
            return $this->pluginmanager->enablePlugin($identifier);
        }

        return $this->pluginmanager->enablePlugin($identifier, false);
    }

    public function clearPreferences(Request $request): bool
    {
        return $this->pluginmanager->removeAllPreferences($request->get('identifier'));
    }

    public function savePreferences(Request $request): void
    {
        $this->pluginmanager->setPreferences($request->get('identifier'), $request->get('preferences'));
    }

    public function cleanUp(): void
    {
        $this->pluginmanager->cleanUninstalledPlugins();
    }

    private function getInstalledPlugins(): \Illuminate\Support\Collection
    {
        return $this->pluginmanager->getAllPlugins(false)->sortBy('identifier')->transform(function ($plugin) {
            $plugin->types = collect(class_implements($plugin))->filter(static function ($interface) {
                return Str::startsWith($interface, 'Voyager\\Admin\\Contracts\\Plugins\\') && Str::endsWith($interface, 'Plugin');
            })->transform(static function ($interface) {
                return (string)Str::of($interface)->replace('Voyager\\Admin\\Contracts\\Plugins\\', '')->replace('Plugin', '')->lower();
            })->values();

            // Remove "generic" from types when types is more than 1 because all other types include "GenericPlugin".
            if ($plugin->types->count() > 1) {
                $plugin->types = $plugin->types->filter(function ($type) {
                    return $type !== 'generic';
                });
            }

            if ($plugin instanceof SettingsComponent) {
                $plugin->settings_component = $plugin->getSettingsComponent();
            }

            if (isset($plugin->readme) && file_exists($plugin->readme)) {
                $plugin->readme = file_get_contents($plugin->readme);
            }

            $plugin->preferences = $this->pluginmanager->getPreferences($plugin->identifier);

            return $plugin;
        })->values();
    }

    private function getUninstalledPlugins(): \Illuminate\Support\Collection
    {
        return $this->pluginmanager->getRegisteredPlugins()->filter(function ($identifier) {
            return $this->pluginmanager->getAllPlugins(false)->where('identifier', $identifier)->count() == 0;
        })->values();
    }
}