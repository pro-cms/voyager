<?php

namespace Voyager\Admin\Manager;

use Composer\InstalledVersions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Voyager\Admin\Contracts\Plugins\GenericPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Provider\CSS as CSSProvider;
use Voyager\Admin\Contracts\Plugins\Features\Provider\FrontendRoutes;
use Voyager\Admin\Contracts\Plugins\Features\Provider\JS as JSProvider;
use Voyager\Admin\Contracts\Plugins\Features\Provider\MenuItems;
use Voyager\Admin\Contracts\Plugins\Features\Provider\ProtectedRoutes;
use Voyager\Admin\Contracts\Plugins\Features\Provider\Settings as SettingsProvider;
use Voyager\Admin\Contracts\Plugins\Features\Provider\Widgets as WidgetsProvider;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;
class Plugins
{
    protected Collection $plugins;
    protected array $enabled_plugins = [];
    protected string $path;
    protected bool $preferences_changed = false;

    public function __construct(public Menu $menumanager, public Settings $settingsmanager)
    {
        $this->plugins = collect();
        $this->path = Str::finish(storage_path('voyager'), '/').'plugins.json';
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function addPlugin(mixed $plugin): void
    {
        if (!$this->enabled_plugins) {
            $this->loadEnabledPlugins();
        }
        if (is_string($plugin)) {
            $plugin = new $plugin();
        }

        if (!($plugin instanceof GenericPlugin)) {
            throw new \Exception('Plugin added to Voyager has to inherit GenericPlugin');
        }

        $plugin->identifier = $plugin->repository.'@'.class_basename($plugin);
        $plugin->enabled = array_key_exists($plugin->identifier, $this->enabled_plugins);
        $plugin->version = InstalledVersions::getPrettyVersion($plugin->repository) ?? '';
        $plugin->version_normalized = InstalledVersions::getVersion($plugin->repository) ?? '';

        $plugin->preferences = new class ($plugin, $this) { // @phpstan-ignore-line
            private GenericPlugin $plugin;
            private Plugins $pluginmanager;

            public function __construct(GenericPlugin $plugin, Plugins $pluginmanager) {
                $this->plugin = $plugin;
                $this->pluginmanager = $pluginmanager;
            }

            public function set(string $key, mixed $value, ?string $locale = null): void {
                $this->pluginmanager->setPreference($this->plugin->identifier, $key, $value, $locale);
            }

            public function get(string $key, mixed $default = null, bool$translate = true): mixed {
                return $this->pluginmanager->getPreference($this->plugin->identifier, $key, $default, $translate);
            }

            public function remove(string $key): mixed {
                return $this->pluginmanager->removePreference($this->plugin->identifier, $key);
            }

            public function removeAll(): mixed {
                return $this->pluginmanager->removeAllPreferences($this->plugin->identifier);
            }
        };

        $this->plugins->push($plugin);
    }

    public function launchPlugins(?bool $protected = null): void
    {
        $this->getAllPlugins()->each(function ($plugin) use ($protected) {
            if ($protected === true) {
                if ($plugin instanceof ProtectedRoutes) {
                    $plugin->provideProtectedRoutes();
                }
            } elseif ($protected === false) {
                if ($plugin instanceof FrontendRoutes) {
                    $plugin->provideFrontendRoutes();
                }
            } else {
                // Register menu items
                if ($plugin instanceof MenuItems) {
                    $plugin->provideMenuItems($this->menumanager);
                }
                // Merge settings
                if ($plugin instanceof SettingsProvider) {
                    $this->settingsmanager->merge(
                        collect($plugin->provideSettings())->transform(function ($setting) {
                            // Transform single setting to object
                            return (object) $setting;
                        })->filter(function ($setting) {
                            // Filter out settings that are already stored
                            return !$this->settingsmanager->exists($setting->group, $setting->key);
                        })->toArray()
                    );
                }
                // Add widgets
                if ($plugin instanceof WidgetsProvider) {
                    $plugin->provideWidgets()->each(function ($widget) {
                        VoyagerFacade::addWidgets($widget);
                    });
                }
            }
        });
    }

    public function loadEnabledPlugins(): void
    {
        $this->enabled_plugins = [];

        VoyagerFacade::ensureFileExists($this->path, '[]');

        collect(VoyagerFacade::getJson(File::get($this->path), []))->where('enabled')->each(function ($plugin) {
            $this->enabled_plugins[$plugin->identifier] = [
                'preferences'   => (array) ($plugin->preferences ?? []),
            ];
        });
    }

    public function getAllPlugins(bool $enabled = true): Collection
    {
        if ($enabled) {
            return $this->plugins->where('enabled');
        }

        return $this->plugins;
    }

    public function getRegisteredPlugins(): Collection
    {
        return collect(VoyagerFacade::getJson(File::get($this->path), []))->map(function ($plugin) {
            return $plugin->identifier;
        });
    }

    public function enablePlugin(string $identifier, bool $enable = true): bool
    {
        $found = false;
        $this->getAllPlugins(false)->each(function ($plugin) use (&$found, $identifier) {
            if ($plugin->identifier == $identifier) {
                $found = true;
            }
        });

        if (!$found) {
            throw new \Exception('Plugin with identifier "'.$identifier.'" is not registered and can not be enabled/disabled!');
        }

        $plugins = collect(VoyagerFacade::getJson(File::get($this->getPath()), []));
        if (!$plugins->contains('identifier', $identifier)) {
            $plugins->push([
                'identifier' => $identifier,
                'enabled'    => $enable,
            ]);
        } else {
            $plugins->where('identifier', $identifier)->first()->enabled = $enable;
        }

        return VoyagerFacade::writeToFile($this->getPath(), json_encode($plugins, JSON_PRETTY_PRINT));
    }

    public function disablePlugin(string $identifier): bool|int
    {
        return $this->enablePlugin($identifier, false);
    }

    public function getAssets(): Collection
    {
        $assets = collect();
        $this->getAllPlugins(false)->each(function ($plugin) use ($assets) {
            if ($plugin instanceof GenericPlugin && $plugin instanceof CSSProvider) {
                $assets->push([
                    'name'      => Str::slug($plugin->name).'.css',
                    'content'   => $plugin->provideCSS()
                ]);
            }
            if ($plugin instanceof GenericPlugin && $plugin instanceof JSProvider && $plugin->enabled) {
                $assets->push([
                    'name'      => Str::slug($plugin->name).'.js',
                    'content'   => $plugin->provideJS()
                ]);
            }
        });

        return $assets;
    }

    public function setPreference(string $identifier, string $key, mixed $value, ?string $locale = null): void
    {
        if (!is_null($locale)) {
            $value = VoyagerFacade::setTranslation(
                $this->enabled_plugins[$identifier]['preferences'][$key],
                $value,
                $locale
            );
        }
        
        $this->enabled_plugins[$identifier]['preferences'][$key] = $value;
        $this->preferences_changed = true;
    }

    public function setPreferences(string $identifier, mixed $preferences): void
    {        
        $this->enabled_plugins[$identifier]['preferences'] = $preferences;
        $this->preferences_changed = true;
    }

    public function getPreference(string $identifier, string $key, mixed $default = null, bool $translate = true): mixed
    {
        $value = $this->enabled_plugins[$identifier]['preferences'][$key] ?? $default;
        if ($translate !== false) {
            return VoyagerFacade::translate($value, null);
        }

        return $value;
    }

    public function getPreferences(string $identifier): array
    {
        return $this->enabled_plugins[$identifier]['preferences'] ?? [];
    }

    public function removePreference(string $identifier, string $key): bool
    {
        // TODO: Make sure everything exists
        unset($this->enabled_plugins[$identifier]['preferences'][$key]);
        $this->preferences_changed = true;

        return true;
    }

    public function removeAllPreferences(string $identifier): bool
    {
        // TODO: Make sure everything exists
        unset($this->enabled_plugins[$identifier]['preferences']);
        $this->preferences_changed = true;

        return true;
    }

    public function cleanUninstalledPlugins(): void
    {
        $plugins = collect(VoyagerFacade::getJson(File::get($this->getPath()), []))->filter(function ($plugin) {
            return $this->getAllPlugins(false)->where('identifier', $plugin->identifier)->count() > 0;
        })->values();

        VoyagerFacade::writeToFile($this->getPath(), json_encode($plugins, JSON_PRETTY_PRINT));
    }

    public function __destruct()
    {
        if ($this->preferences_changed) {
            $plugins = collect(VoyagerFacade::getJson(File::get($this->getPath()), []))->transform(function ($plugin) {
                $plugin->preferences = $this->enabled_plugins[$plugin->identifier]['preferences'] ?? [];

                return $plugin;
            });

            VoyagerFacade::writeToFile($this->getPath(), json_encode($plugins, JSON_PRETTY_PRINT));
        }
    }
}