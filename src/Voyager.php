<?php

namespace Voyager\Admin;

use Composer\InstalledVersions;
use Illuminate\Support\{Collection, Str};

use Voyager\Admin\Classes\Widget;
use Voyager\Admin\Contracts\Plugins\{AuthenticationPlugin, AuthorizationPlugin};
use Voyager\Admin\Contracts\Plugins\Features\Filter\Widgets as WidgetFilter;
use Voyager\Admin\Manager\{Breads as BreadManager, Menu as MenuManager, Plugins as PluginManager, Settings as SettingManager};
use Voyager\Admin\Plugins\AuthenticationPlugin as DefaultAuthPlugin;
use Voyager\Admin\Traits\Voyager\{Assets, Database, Filesystem, Localization, Messages, Routes};

class Voyager
{
    use Assets,
        Database,
        Filesystem,
        Localization,
        Messages,
        Routes;

    protected array $tables = [];
    protected Collection $widgets;
    protected string $version = '';

    public function __construct(
        protected BreadManager $breadmanager,
        protected MenuManager $menumanager,
        protected PluginManager $pluginmanager,
        protected SettingManager $settingmanager
    )
    {
        $this->widgets = collect();
        $this->assets = collect();
    }

    /**
     * Get the currently installed version of Voyager
     */
    public function getVersion(): string
    {
        if ($this->version == '') {
            $this->version = InstalledVersions::getPrettyVersion('voyager-admin/voyager') ?? '';
            if (Str::contains($this->version, '-dev')) {
                $this->version = Str::substr(InstalledVersions::getReference('voyager-admin/voyager') ?? '', 0, 7);
            }
        }

        return $this->version;
    }

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

    /**
     * Get a setting, settings in a group or all settings.
     */
    public function setting(?string $key = null, mixed $default = null, bool $translate = true): mixed
    {
        return $this->settingmanager->setting($key, $default, $translate);
    }

    /**
     * Set the path where BREAD JSON files are loaded from/stored to.
     */
    public function setBreadPath(string $path): void
    {
        $this->breadmanager->setPath($path);
    }

    /**
     * Set the path where the plugins JSON file is loaded from/stored to.
     */
    public function setPluginsPath(string $path): void
    {
        $this->pluginmanager->setPath($path);
    }

    /**
     * Set the path where the settings JSON file is loaded from/stored to.
     */
    public function setSettingsPath(string $path): void
    {
        $this->settingmanager->setPath($path);
    }

    /**
     * Gets the authentication plugin.
     */
    public function auth(): AuthenticationPlugin
    {
        return $this->pluginmanager->getAllPlugins()->filter(function ($plugin) {
            return $plugin instanceof AuthenticationPlugin;
        })->first() ?? new DefaultAuthPlugin();
    }

    /**
     * Authorize an action for a user.
     */
    public function authorize(mixed $user, mixed $ability, array $arguments = []): bool
    {
        $authorized = true;
        $this->pluginmanager->getAllPlugins()->filter(function ($plugin) {
            return $plugin instanceof AuthorizationPlugin;
        })->each(function ($plugin) use ($user, $ability, $arguments, &$authorized) {
            if ($plugin->authorize($user, $ability, $arguments) === false) {
                $authorized = false;
            }
        });

        return $authorized;
    }

    /**
     * Resolve a batch of permissions for a given user.
     */
    public function resolvePermissions(array $permissions, ?\Illuminate\Contracts\Auth\Authenticatable $user = null): Collection
    {
        if ($user === null) {
            $user = $this->auth()->user();
        }

        return collect($permissions)->mapWithKeys(function ($arguments, $ability) use ($user) {
            if (is_int($ability)) {
                return [$arguments => $this->authorize($user, $arguments)];
            }

            return [$ability => $this->authorize($user, $ability, $arguments)];
        });
    }

    /**
     * Get a BREAD by it's (table) name.
     */
    public function getBreadByName(string $breadName): ?Classes\Bread
    {
        return $this->breadmanager->getBreadByName($breadName);
    }

    /**
     * Get data used in all internal Voyager views.
     */
    public function getViewData(): array
    {
        // This data gets directly written to the store and can be accessed through `this.$store` everywhere
        $viewData = [
            'localization'          => $this->getLocalization(),
            'locales'               => $this->getLocales(),
            'locale'                => $this->getLocale(),
            'initialLocale'         => $this->getLocale(),

            'notificationPosition'  => $this->setting('admin.notification-position', 'top-right'),
            'jsonOutput'            => $this->setting('admin.json-output', false),

            'titleSuffix'           => $this->setting('admin.title', 'Voyager II'),

            'rtl'                   => (__('voyager::generic.is_rtl') == 'true'),
            'csrfToken'             => csrf_token(),

            'messages'              => $this->getMessages(),
            'devServer'             => [
                'url'       => view()->shared('devServerUrl', null),
                'available' => view()->shared('devServerAvailable', false),
                'wanted'    => view()->shared('devServerWanted', false),
            ],
        ];

        if ($this->auth()->user()) {
            $viewData = array_merge($viewData, [
                'breads'                => $this->breadmanager->getBreads(),
                'formfields'            => $this->breadmanager->getFormfields(),
                'tables'                => $this->getTables(),
                'searchPlaceholder'     => $this->breadmanager->getBreadSearchPlaceholder(),
                'sidebar'               => [
                    'items'     => $this->menumanager->getItems($this->pluginmanager),
                    'title'     => $this->setting('admin.sidebar-title', 'Voyager II'),
                    'iconSize'  => $this->setting('admin.icon-size', 6)
                ],
                'user'                  => [
                    'name'      => $this->auth()->name(),
                    'avatar'    => $this->auth()->avatar(),
                    'items'     => $this->menumanager->getItems($this->pluginmanager, true)
                ]
            ]);
        }

        return $viewData;
    }
}
