<?php

namespace Voyager\Admin\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Voyager\Admin\Contracts\Plugins\ThemePlugin;
use Voyager\Admin\Contracts\Plugins\Features\Provider\InstructionsComponent;
use Voyager\Admin\Contracts\Plugins\Features\Provider\SettingsComponent;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;
use Voyager\Admin\Manager\Plugins as PluginManager;

class PluginsController extends Controller
{
    protected PluginManager $pluginmanager;

    public function __construct(PluginManager $pluginmanager)
    {
        $this->pluginmanager = $pluginmanager;

        parent::__construct();
    }

    public function index(): InertiaResponse
    {
        return $this->inertiaRender('Plugins', __('voyager::plugins.plugins'), [
            'installed-plugins' => $this->getInstalledPlugins()
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

    private function getInstalledPlugins(): \Illuminate\Support\Collection
    {
        return $this->pluginmanager->getAllPlugins(false)->sortBy('identifier')->transform(function ($plugin) {
            $plugin->type = collect(class_implements($plugin))->filter(static function ($interface) {
                return Str::startsWith($interface, 'Voyager\\Admin\\Contracts\\Plugins\\') && Str::endsWith($interface, 'Plugin');
            })->transform(static function ($interface) {
                return Str::of($interface)->replace('Voyager\\Admin\\Contracts\\Plugins\\', '')->replace('Plugin', '')->lower();
            })->first();

            if ($plugin instanceof SettingsComponent) {
                $plugin->settings_component = $plugin->getSettingsComponent();
            }
            if ($plugin instanceof InstructionsComponent) {
                $plugin->instructions_component = $plugin->getInstructionsComponent();
            }

            $plugin->preferences = $this->pluginmanager->getPreferences($plugin->identifier);

            return $plugin;
        })->values();
    }
}