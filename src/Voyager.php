<?php

namespace Voyager\Admin;

use Composer\InstalledVersions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Voyager\Admin\Contracts\Plugins\AuthenticationPlugin;
use Voyager\Admin\Contracts\Plugins\AuthorizationPlugin;
use Voyager\Admin\Contracts\Plugins\WidgetPlugin;
use Voyager\Admin\Contracts\Plugins\Features\Filter\Widgets as WidgetFilter;
use Voyager\Admin\Manager\Breads as BreadManager;
use Voyager\Admin\Manager\Menu as MenuManager;
use Voyager\Admin\Manager\Plugins as PluginManager;
use Voyager\Admin\Manager\Settings as SettingManager;
use Voyager\Admin\Plugins\AuthenticationPlugin as DefaultAuthPlugin;

class Voyager
{
    /**
     * The route prefix that Voyager will use when registering routes.
     *
     * @var string
     */
    public static $routePath = '/admin';

    protected array $messages = [];
    protected array $tables = [];
    protected array $locales = [];
    protected \Voyager\Admin\Manager\Breads $breadmanager;
    protected \Voyager\Admin\Manager\Menu $menumanager;
    protected \Voyager\Admin\Manager\Plugins $pluginmanager;
    protected \Voyager\Admin\Manager\Settings $settingmanager;
    protected array $translations = [];
    protected string $version = '';

    public function __construct(BreadManager $breadmanager, MenuManager $menumanager, PluginManager $pluginmanager, SettingManager $settingmanager)
    {
        $this->breadmanager = $breadmanager;
        $this->menumanager = $menumanager;
        $this->pluginmanager = $pluginmanager;
        $this->settingmanager = $settingmanager;
    }

    /**
     * Set the callback that should be used to authenticate Horizon users.
     */
    public static function path(string $pathPrefix = '/admin'): static
    {
        static::$routePath = $pathPrefix;

        return new static(
            app(BreadManager::class),
            app(MenuManager::class),
            app(PluginManager::class),
            app(SettingManager::class)
        );
    }

    /**
     * Generate a Voyager route URL for Voyager resources and paths.
     */
    public function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return route('voyager.' . $name, $parameters, $absolute);
    }

    /**
     * Generate an absolute URL for an asset-file.
     */
    public function assetUrl(?string $path = null): string
    {
        if ($path === null) {
            return route('voyager.voyager_assets').'?path='.urlencode($path ?? '');
        }

        return route('voyager.voyager_assets').'?path='.urlencode($path ?? '').'&version='.$this->getVersion();
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
     * Flash a message to the UI.
     */
    public function flashMessage(array|string|null $message, string $color, ?int $timeout = 5000, bool $next = false): void
    {
        $this->messages[] = [
            'message' => $message,
            'color'   => $color,
            'timeout' => $timeout,
        ];
        if ($next) {
            session()->push('voyager-messages', [
                'message' => $message,
                'color'   => $color,
                'timeout' => $timeout,
            ]);
        }
    }

    /**
     * Get all messages.
     */
    public function getMessages(): Collection
    {
        $messages = array_merge($this->messages, session()->get('voyager-messages', []));
        session()->forget('voyager-messages');

        return collect($messages)->unique();
    }

    /**
     * Get all Voyager translation strings.
     */
    public function getLocalization(): Collection
    {
        $translator = app()->make('translator');

        return collect(['auth', 'bread', 'builder', 'datetime', 'formfields', 'generic', 'media', 'plugins', 'settings', 'validation'])->flatMap(function ($group) {
            return ['voyager::'.$group => trans('voyager::'.$group)];
        })->merge(collect($this->translations)->flatMap(function ($namespace, $group) use ($translator) {
            $translator->load($namespace, $group, $this->getLocale());
            return [$namespace.'::'.$group => trans($namespace.'::'.$group)];
        }));
    }

    /**
     * Add translations to the Voyager namespace.
     */
    public function addTranslations(string $namespace, string $group): void
    {
        $this->translations[$namespace] = $group;
    }

    /**
     * Get all tables in the database.
     *
     * @return array
     */
    public function getTables(): array
    {
        return DB::connection()->getDoctrineSchemaManager()->listTableNames();
    }

    /**
     * Get all columns in a given table.
     */
    public function getColumns(string $table): array
    {
        if (!array_key_exists($table, $this->tables)) {
            $builder = DB::getSchemaBuilder();
            $this->tables[$table] = $builder->getColumnListing($table);
        }

        return $this->tables[$table];
    }

    /**
     * Get all locales supported by the app.
     */
    public function getLocales(): array
    {
        if (count($this->locales) == 0) {
            return config('app.locales', [$this->getLocale()]);
        }

        return $this->locales;
    }

    /**
     * Add a locale to the supported locales.
     */
    public function addLocale(string $locale): void
    {
        $this->locales[] = $locale;
    }

    /**
     * Set and override all locales.
     */
    public function setLocales(array $locales): void
    {
        $this->locales = $locales;
    }

    /**
     * Get the current app-locale.
     */
    public function getLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Get the app fallback-locale.
     */
    public function getFallbackLocale(): string
    {
        return config('app.fallback_locale', [$this->getLocale()]);
    }

    /**
     * Get if the app is translatable or not.
     */
    public function isTranslatable(): bool
    {
        return count($this->getLocales()) > 1;
    }

    /**
     * Gets all widgets from installed and enabled plugins filtered by plugins.
     */
    public function getWidgets(): Collection
    {
        $widgets = collect($this->pluginmanager->getAllPlugins()->filter(function ($plugin) {
            return $plugin instanceof WidgetPlugin;
        })->transform(function ($plugin) {
            $width = $plugin->getWidth();
            if ($width >= 1 && $width <= 11) {
                $width = 'w-'.$width.'/12';
            } else {
                $width = 'w-full';
            }

            return (object) [
                'width'         => $width,
                'title'         => $plugin->getTitle(),
                'icon'          => $plugin->getIcon(),
                'component'     => $plugin->getWidgetComponent(),
                'parameters'    => $plugin->getWidgetParameters()
            ];
        }));

        $this->pluginmanager->getAllPlugins()->each(function ($plugin) use (&$widgets) {
            if ($plugin instanceof WidgetFilter) {
                $widgets = $plugin->filterWidgets($widgets);
            }
        });

        return $widgets;
    }

    /**
     * Translate a given string/object/array.
     */
    public function translate(mixed $value, ?string $locale = null, ?string $fallback = null): string
    {
        if ($locale == null) {
            $locale = app()->getLocale();
        }
        if ($fallback == null) {
            $fallback = config('app.fallback_locale');
        }

        if (is_string($value)) {
            if (($json = $this->getJson($value)) === false) {
                return $value;
            } else {
                $value = $json;
            }
        }

        if (is_array($value)) {
            return $value[$locale] ?? $value[$fallback] ?? null;
        } elseif (is_object($value)) {
            return $value->{$locale} ?? $value->{$fallback} ?? null;
        }

        return $value;
    }

    /**
     * Set a translation in a given string/object/array.
     */
    public function setTranslation(mixed $input, mixed $value, ?string $locale = null): mixed
    {
        if ($locale == null) {
            $locale = app()->getLocale();
        }

        if (is_string($input)) {
            $json = $this->getJson($input);
            if ($json === false) {
                $input = [];
            } else {
                $input = $json;
            }
        }

        if (is_array($input)) {
            $input[$locale] = $value;
        } elseif (is_object($input)) {
            $input->{$locale} = $value;
        }

        return $input;
    }

    /**
     * Get a setting, settings in a group or all settings.
     */
    public function setting(?string $key = null, mixed $default = null, bool $translate = true): mixed
    {
        return $this->settingmanager->setting($key, $default, $translate);
    }

    /**
     * Safely parse a string into JSON
     */
    public function getJson(string $input, mixed $default = false): mixed
    {
        $json = @json_decode($input);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $json;
        }

        return $default;
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
     * Ensures that a directory exists.
     */
    public function ensureDirectoryExists(string $path): void
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * Ensures that a file exists.
     */
    public function ensureFileExists(string $path, string $content = ''): void
    {
        $this->ensureDirectoryExists(dirname($path));
        if (!file_exists($path)) {
            file_put_contents($path, $content);
        }
    }

    /**
     * Safely write to a file.
     */
    public function writeToFile(string $path, string|bool $content = ''): bool
    {
        // When passing in json_encode(), the result might be false
        if (is_bool($content)) {
            return false;
        }

        return File::put($path, $content) === false ? false : true;
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
            'breads'                => $this->breadmanager->getBreads(),
            'formfields'            => $this->breadmanager->getFormfields(),

            'localization'          => $this->getLocalization(),
            'locales'               => $this->getLocales(),
            'locale'                => $this->getLocale(),
            'initialLocale'         => $this->getLocale(),

            'notificationPosition'  => $this->setting('admin.notification-position', 'top-right'),
            'jsonOutput'            => $this->setting('admin.json-output', false),
            'searchPlaceholder'     => $this->breadmanager->getBreadSearchPlaceholder(),

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
                'sidebar'               => [
                    'items'     => $this->menumanager->getItems($this->pluginmanager),
                    'title'     => $this->setting('admin.sidebar-title', 'Voyager II'),
                    'iconSize'  => $this->setting('admin.icon-size', 6)
                ],
                'user'                  => [
                    'name'      => $this->auth()->name(),
                    'avatar'    => $this->auth()->avatar(),
                ]
            ]);
        }

        return $viewData;
    }
}
