<?php

namespace Voyager\Admin\Manager;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Voyager\Admin\Classes\Action as ActionClass;
use Voyager\Admin\Classes\Bread as BreadClass;
use Voyager\Admin\Classes\Formfield;
use Voyager\Admin\Classes\Layout;
use Voyager\Admin\Contracts\Plugins\Features\Filter\Layouts as LayoutFilter;
use Voyager\Admin\Exceptions\NoLayoutFoundException;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;
use Voyager\Admin\Manager\Plugins as PluginManager;

class Breads
{
    protected Collection $formfields;
    protected string $path;
    protected Collection|null $breads = null;
    protected array $backups = [];
    protected Collection $actions;
    protected PluginManager $pluginmanager;

    public function __construct(PluginManager $pluginmanager)
    {
        $this->path = Str::finish(storage_path('voyager/breads'), '/');
        $this->pluginmanager = $pluginmanager;
        $this->formfields = collect();
        $this->actions = collect();
    }

    /**
     * Sets the path where the BREAD-files are stored.
     *
     * @param string $path
     * @return string the current path.
     */
    public function setPath($path = null)
    {
        if ($path) {
            $old_path = $this->path;
            $this->path = Str::finish($path, '/');
            if ($old_path !== $path) {
                $this->breads = null;
            }
        }

        return $this->path;
    }

    /**
     * Get all BREADs from storage and validate.
     *
     * @return \Illuminate\Support\Collection<string, BreadClass>
     */
    public function getBreads()
    {
        if (!$this->breads) {
            VoyagerFacade::ensureDirectoryExists($this->path);
            $this->breads = collect(File::files($this->path))->transform(function ($bread) {
                $content = File::get($bread->getPathName());
                $json = VoyagerFacade::getJson($content);
                if ($json === false) {
                    VoyagerFacade::flashMessage('BREAD-file "'.basename($bread->getPathName()).'" does contain invalid JSON: '.json_last_error_msg(), 'yellow');

                    return;
                }

                $b = new BreadClass($json);

                // Push Exclude backups
                if (Str::contains($bread->getPathName(), '.backup.')) {
                    $date = Str::before(Str::after($bread->getFilename(), '.backup.'), '.json');
                    $this->backups[] = [
                        'table' => $b->table,
                        'path'  => $bread->getFilename(),
                        'date'  => $date,
                    ];

                    return null;
                }

                return $b;
            })->filter(function ($bread) {
                return $bread !== null;
            })->values()->mapWithKeys(static function ($value, $key) {
                return [$value->name_singular => $value];
            });
        }

        return $this->breads;
    }

    /**
     * Get backed-up BREADs.
     *
     * @return array
     */
    public function getBackups()
    {
        $this->breads = null;
        $this->backups = [];
        $this->getBreads();

        return $this->backups;
    }

    /**
     * Rollback BREAD to a given file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function rollbackBread(string $table, string $path): string|bool
    {
        $path = Str::finish($this->path, '/');
        if ($this->backupBread($table) !== false) {
            return File::delete($path.$table.'.json') && File::copy($path.$path, $path.$table.'.json');
        }

        return false;
    }

    /**
     * Determine if a BREAD exists by the table name.
     *
     * @param string $table
     *
     * @return bool
     */
    public function hasBread($table)
    {
        return $this->getBread($table) !== null;
    }

    /**
     * Get a BREAD by the table name.
     *
     * @param string $table
     *
     * @return \Voyager\Admin\Classes\Bread
     */
    public function getBread($table): \Voyager\Admin\Classes\Bread|null
    {
        return $this->getBreads()->where('table', $table)->first();
    }

    /**
     * Get a BREAD by the table name.
     *
     * @param string $breadName
     *
     * @return \Voyager\Admin\Classes\Bread
     */
    public function getBreadByName($breadName)
    {
        return $this->getBreads()->get($breadName);
    }

    /**
     * Store a BREAD-file.
     *
     * @param \Voyager\Admin\Classes\Bread|\stdClass $bread
     *
     * @return int|bool success
     */
    public function storeBread(\Voyager\Admin\Classes\Bread|\stdClass $bread)
    {
        $this->clearBreads();

        return File::put(Str::finish($this->path, '/').$bread->table.'.json', json_encode($bread, JSON_PRETTY_PRINT));
    }

    /**
     * Create a BREAD-object.
     *
     * @param string $table
     *
     * @return \Voyager\Admin\Classes\Bread
     */
    public function createBread($table)
    {
        // Guess the model name
        $name = Str::singular(Str::studly($table));

        $namespace = Str::start(Str::finish(Container::getInstance()->getNamespace() ?? 'App\\', '\\'), '\\'); // @phpstan-ignore-line
        $model = (is_dir(app_path('Models')) ? $namespace.'Models\\' : $namespace).$name;

        if (!class_exists($model)) {
            $model = null;
        }

        $bread = [
            'table'         => $table,
            'slug'          => Str::slug($table),
            'name_singular' => str_replace('_', ' ', Str::singular(Str::title($table))),
            'name_plural'   => str_replace('_', ' ', Str::plural(Str::title($table))),
            'model'         => $model,
            'layouts'       => [],
        ];

        return new BreadClass($bread);
    }

    /**
     * Clears all BREAD-objects.
     */
    public function clearBreads(): void
    {
        $this->breads = null;
    }

    /**
     * Delete a BREAD from the filesystem.
     *
     * @param string $table The table of the BREAD
     */
    public function deleteBread(string $table): bool
    {
        $ret = File::delete(Str::finish($this->path, '/').$table.'.json');
        $this->clearBreads();

        return $ret;
    }

    /**
     * Backup a BREAD (copy table.json to table.backup.json).
     *
     * @param  string $table The table of the BREAD
     * @return string|bool The name of the backup file or false when failed
     */
    public function backupBread(string $table): string|bool
    {
        $old = $this->path.$table.'.json';
        $name = $table.'.backup.'.Carbon::now()->isoFormat('Y-MM-DD@HH-mm-ss').'.json';
        $new = $this->path.$name;

        if (File::exists($old)) {
            if (!File::copy($old, $new)) {
                return false;
            }
        }

        return $name;
    }

    /**
     * Get the search placeholder (Search for Users, Posts, etc...).
     *
     * @return string $placeholder The placeholder
     */
    public function getBreadSearchPlaceholder()
    {
        $breads = $this->getBreads()
        ->reject(function ($bread) {
            // TODO: Authorize if user is able to browse this bread
            return empty($bread->global_search_field);
        })->shuffle();

        if ($breads->count() > 1) {
            return __('voyager::generic.search_for_breads', [
                'bread'  => $breads->get(0)->name_plural,
                'bread2' => $breads->get(1)->name_plural,
            ]);
        } elseif ($breads->count() == 1) {
            return __('voyager::generic.search_for_bread', [
                'bread' => $breads->get(0)->name_plural,
            ]);
        }

        return __('voyager::generic.search');
    }

    /**
     * Add a formfield.
     *
     * @param mixed $class The class of the formfield
     */
    public function addFormfield(mixed $class): void
    {
        if (!$class instanceof Formfield) {
            $class = new $class();
        }

        if (!method_exists($class, 'name')) {
            throw new \Exception('Formfields need to implement the "name" method.');
        } elseif (!method_exists($class, 'type')) {
            throw new \Exception('Formfields need to implement the "type" method.');
        }

        $this->formfields->push($class);
    }

    /**
     * Get formfields.
     *
     * @return \Illuminate\Support\Collection The formfields
     */
    public function getFormfields()
    {
        return $this->formfields->map(function ($formfield) {
            return [
                'name'                      => $formfield->name(),
                'type'                      => $formfield->type(),
                'can_be_translated'         => !property_exists($formfield, 'notTranslatable'),
                'in_settings'               => !property_exists($formfield, 'notAsSetting'),
                'in_lists'                  => !property_exists($formfield, 'notInLists'),
                'in_views'                  => !property_exists($formfield, 'notInViews'),
                'browse_array'              => property_exists($formfield, 'browseArray'),
                'allow_columns'             => !property_exists($formfield, 'noColumns'),
                'allow_computed_props'      => !property_exists($formfield, 'noComputedProps'),
                'allow_relationships'       => !property_exists($formfield, 'noRelationships'),
                'allow_relationship_props'  => !property_exists($formfield, 'noRelationshipProps'),
                'allow_relationship_pivots' => !property_exists($formfield, 'noRelationshipPivots'),
                'component'                 => method_exists($formfield, 'getComponentName') ? $formfield->getComponentName() : 'Formfield'.Str::studly($formfield->type()),
                'builder_component'         => method_exists($formfield, 'getBuilderComponentName') ? $formfield->getBuilderComponentName() : 'Formfield'.Str::studly($formfield->type()).'Builder',
            ];
        });
    }

    /**
     * Get a formfield by type.
     *
     * @param string $type The type of the formfield
     *
     * @return Formfield|null The formfield or null
     */
    public function getFormfield(string $type): Formfield|null
    {
        return $this->formfields->filter(function ($formfield) use ($type) {
            return $formfield->type() == $type;
        })->first();
    }

    /**
     * Get the reflection class for a model.
     *
     * @param string $model The fully qualified model name
     *
     * @return \ReflectionClass The reflection object
     */
    public function getModelReflectionClass(string $model): \ReflectionClass
    {
        return new \ReflectionClass($model);
    }

    public function getModelScopes(\ReflectionClass $reflection): Collection
    {
        return collect($reflection->getMethods())->filter(function ($method) {
            return Str::startsWith($method->name, 'scope');
        })->whereNotIn('name', ['scopeWithTranslations', 'scopeWithTranslation', 'scopeWhereTranslation'])->transform(function ($method) {
            return lcfirst(Str::replaceFirst('scope', '', $method->name));
        });
    }

    public function getModelComputedProperties(\ReflectionClass $reflection): Collection
    {
        return collect($reflection->getMethods())->filter(function ($method) {
            return Str::startsWith($method->name, 'get') && Str::endsWith($method->name, 'Attribute');
        })->transform(function ($method) {
            $name = Str::replaceFirst('get', '', $method->name);
            $name = Str::replaceLast('Attribute', '', $name);

            return lcfirst($name);
        })->filter();
    }

    public function getModelRelationships(\ReflectionClass $reflection, Model $model, bool $resolve = false): Collection
    {
        $single = [
            BelongsTo::class,
            HasOne::class,
            HasOneThrough::class,
        ];

        $multi = [
            BelongsToMany::class,
            HasMany::class,
            HasManyThrough::class,
        ];

        return collect($reflection->getMethods())->transform(function ($method) use ($single, $multi, $model, $resolve) {
            $type = $method->getReturnType();
            if ($type && in_array(strval($type->getName()), array_merge($single, $multi))) {
                $columns = [];
                $scopes = [];
                $pivot = [];
                $computed = [];
                $table = '';
                $relationship = null;
                if ($resolve) {
                    $relationship = $model->{$method->getName()}();
                    $related = $relationship->getRelated();
                    $table = $related->getTable();
                    if ($type->getName() == BelongsToMany::class) {
                        $pivot = array_values(array_diff(VoyagerFacade::getColumns($relationship->getTable()), [
                            $relationship->getForeignPivotKeyName(),
                            $relationship->getRelatedPivotKeyName(),
                        ]));
                    }
                    $columns = VoyagerFacade::getColumns($table);
                    $relationship_reflection = $this->getModelReflectionClass(get_class($related));
                    $computed = $this->getModelComputedProperties($relationship_reflection)->values();
                    $scopes = $this->getModelScopes($relationship_reflection)->values();
                }

                return [
                    'method'    => $method->getName(),
                    'type'      => class_basename($type->getName()),
                    'table'     => $table,
                    'columns'   => $columns,
                    'scopes'    => $scopes,
                    'pivot'     => $pivot,
                    'computed'  => $computed,
                    'key_name'  => $relationship ? $relationship->getRelated()->getKeyName() : '',
                    'multiple'  => in_array(strval($type->getName()), $multi),
                ];
            }

            return null;
        })->filter();
    }

    /**
     * Add an action to BREADs.
     *
     * @param \Voyager\Admin\Classes\Action $action The action instance.
     */
    public function addAction(\Voyager\Admin\Classes\Action $action): void
    {
        $this->actions->push($action);
    }

    /**
     * Manipulate all actions.
     *
     * @param callable $callback A callback which gets all actions and returns a manipulated version of them.
     */
    public function manipulateActions(callable $callback): void
    {
        $this->actions = $callback($this->actions);
    }

    /**
     * Gets all actions for a BREAD.
     *
     * @param BreadClass $bread The BREAD.
     * @return Collection The collection of Actions
     */
    public function getActionsForBread(BreadClass $bread): Collection
    {
        return $this->actions->filter(function ($action) use ($bread) {
            $display = true;
            if (is_callable($action->callback)) {
                $display = $action->callback->call($this, $bread) ?? true; // @phpstan-ignore-line
            }

            if (!is_null($action->permission)) {
                if (!VoyagerFacade::authorize(VoyagerFacade::auth()->user(), $action->permission, [$bread])) {
                    $display = false;
                }
            }

            return $display;
        })->transform(function ($action) use ($bread) {
            // Resolve route
            if (is_callable($action->route_callback)) {
                $action->route_name = $action->route_callback->call($this, $bread) ?? '#'; // @phpstan-ignore-line
            } else {
                $action->route_name = $action->route_callback;
            }

            return $action;
        });
    }

    public function getLayoutForAction(BreadClass $bread, string $action, bool $throwError = true): Layout|null
    {
        $layouts = $bread->layouts->where('type', $action == 'browse' ? 'list' : 'view');

        $this->pluginmanager->getAllPlugins()->each(function ($plugin) use ($bread, $action, &$layouts) {
            if ($plugin instanceof LayoutFilter) {
                $layouts = $plugin->filterLayouts($bread, $action, $layouts);
            }
        });

        if ($layouts->count() < 1 && $throwError) {
            throw new NoLayoutFoundException(__('voyager::bread.no_layout_assigned', ['action' => ucfirst($action)]));
        }

        return $layouts->first();
    }
}
