<?php

namespace Voyager\Admin\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Voyager\Admin\Contracts\Formfields\Features;
use Voyager\Admin\Exceptions\NoLayoutFoundException;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;
use Voyager\Admin\Manager\Breads as BreadManager;
use Voyager\Admin\Traits\Bread\Browsable;
use Voyager\Admin\Traits\Bread\Saveable;
use Voyager\Admin\Traits\Translatable;

class BreadController extends Controller
{
    use Browsable, Saveable;

    public bool $uses_soft_deletes = false;
    protected BreadManager $breadmanager;

    public function __construct(BreadManager $breadmanager)
    {
        $this->breadmanager = $breadmanager;
        parent::__construct();
    }

    public function browse(Request $request): InertiaResponse
    {
        $bread = $this->getBread($request, true);
        $layout = $this->breadmanager->getLayoutForAction($bread, 'browse');

        return $this->inertiaRender('Bread/Browse', __('voyager::bread.browse_type', ['type' => $bread->name_plural]), [
            'bread'         => $bread,
            'relationships' => $bread->relationships,
            'defaultOrder'  => $layout->options->default_order_column->column ?? null,
        ]);
    }

    public function data(Request $request): array
    {
        $start = microtime(true);
        $bread = $this->getBread($request);
        $forcedLayout = $request->get('forcedLayout', null);
        if ($forcedLayout !== null) {
            $layout = $bread->layouts->where('uuid', $forcedLayout)->first();
        } else {
            $layout = $this->breadmanager->getLayoutForAction($bread, 'browse');
        }
        

        $warnings = [];

        $perpage = $request->get('perpage', 10);
        $global = $request->get('global', '');
        $filters = $request->get('filters', []);
        $filter = $request->get('filter', null);
        $softdeleted = $request->get('softdeleted', 'show');
        $locale = $request->get('locale', VoyagerFacade::getLocale());

        $query = $bread->getModel();

        if (!empty($layout->options->scope)) {
            $query = $query->{$layout->options->scope}();
        }

        // Apply custom scope
        $query = $this->applyCustomScope($bread, $layout, $filter, $query, $warnings);

        // Soft-deletes
        $query = $this->loadSoftDeletesQuery($bread, $layout, $softdeleted, $query);

        $total = $query->count();

        // Eager load relationships
        $query = $this->eagerLoadRelationships($layout, $query, $warnings);

        // Custom filter
        $query = $this->applyCustomFilter($bread, $layout, $filter, $query);

        // Global search ($global)
        $query = $this->globalSearchQuery($global, $layout, $locale, $query);

        // Column search ($filters)
        $query = $this->columnSearchQuery($filters, $layout, $query, $locale, $warnings);

        // Ordering ($order)
        $query = $this->orderQuery($layout, $request->get('direction', 'asc'), $request->get('order', null), $query, $locale, $warnings);

        $filtered = $total;
        if (!empty($global) || count($filters) > 0) {
            // TODO: We'd have to add an aggregate "count(*) as filtered" to remove one duplicate query
            $filtered = $query->count();
        }

        // Pagination ($perpage). At this point $query gets a Collection
        $query = $query->skip(($request->get('page', 1) - 1) * $perpage)->take($perpage)->get();

        // Load accessors
        $accessors = $layout->getFormfieldsByColumnType('computed')->pluck('column.column')->toArray();
        $query = $query->each(function ($item) use ($accessors) {
            $item->append($accessors);
        });

        // Transform results
        $query = $this->transformResults($layout, $bread->usesTranslatableTrait(), $query, $global, $filters);

        return [
            'results'           => $query->values(),
            'filtered'          => $filtered,
            'total'             => $total,
            'layout'            => $layout,
            'execution'         => number_format(((microtime(true) - $start) * 1000), 0, '.', ''),
            'uses_soft_deletes' => $this->uses_soft_deletes,
            'uses_ordering'     => ($bread->order_field !== null),
            'actions'           => $this->breadmanager->getActionsForBread($bread)->values(),
            'warnings'          => $warnings,
        ];
    }

    public function add(Request $request): array|InertiaResponse
    {
        $bread = $this->getBread($request, true);
        $layout = $this->breadmanager->getLayoutForAction($bread, 'add');

        $new = true;
        $data = collect();

        $relationships = $bread->relationships->values();

        $layout->formfields->each(function ($formfield) use (&$data) {
            $data->put($formfield->column->column, $formfield->add());
        });

        if ($request->has('from_relationship')) {
            return compact('bread', 'layout', 'new', 'data', 'relationships');
        }

        return $this->inertiaRender('Bread/EditAdd', __('voyager::generic.add_type', ['type' => $bread->name_singular]), [
            'bread'         => $bread,
            'action'        => 'add',
            'layout'        => $layout,
            'relationships' => $relationships,
            'input'         => $data,
            'prev-url'      => url()->previous(),
            'primary-key'   => 0,
        ]);
    }

    public function store(Request $request): Response|JsonResponse
    {
        $bread = $this->getBread($request, true);
        $layout = $this->breadmanager->getLayoutForAction($bread, 'add');

        $model = new $bread->model();
        $data = $request->get('data', []);

        if ($bread->usesTranslatableTrait()) {
            $model->dontTranslate();
        }

        // Validate Data
        $validate_all_locales = $layout->options->validate_locales == 'all' ?? false;
        $validation_errors = $this->validateData($layout->formfields, $data, $validate_all_locales);
        if (count($validation_errors) > 0) {
            return response()->json($validation_errors, 422);
        }

        $model = $this->updateStoreData($layout->formfields, $data, $model, false);

        if ($model->save()) {
            $layout->formfields->each(function ($formfield) use ($data, $model) {
                $formfield->stored($model, $data[$formfield->column->column]);
            });

            // Some formfields need to do something after the model was stored.
            // Relationships for example need to know the key of the created entry.
            $model->save();

            return response($model->getKey(), 200);
        } else {
            return response($model->getKey(), 500);
        }
    }

    public function read(Request $request, mixed $id): InertiaResponse
    {
        $bread = $this->getBread($request, true);
        $layout = $this->breadmanager->getLayoutForAction($bread, 'read');

        $data = $bread->getModel()->findOrFail($id);
        if (!empty($layout->options->scope)) {
            $data = $bread->getModel()->{$layout->options->scope}()->findOrFail($id);
        }

        $layout->formfields->each(function ($formfield) use (&$data) {
            $value = $data->{$formfield->column->column};

            if ($formfield->translatable ?? false) {
                $translations = [];
                $value = json_decode($value) ?? [];
                foreach ($value as $locale => $translated) {
                    $translations[$locale] = $formfield->read($translated);
                }
                $data->{$formfield->column->column} = $translations;
            } else {
                $data->{$formfield->column->column} = $formfield->read($value);
            }
        });

        return $this->inertiaRender('Bread/Read', __('voyager::generic.show_type', ['type' => $bread->name_singular]), [
            'bread'         => $bread,
            'layout'        => $layout,
            'data'          => $data,
            'primary'       => $data->getKey(),
            'input'         => $data,
            'prev-url'      => url()->previous(),
            'relationships' => $bread->relationships->values(),
        ]);
    }

    public function edit(Request $request, mixed $id): InertiaResponse
    {
        $bread = $this->getBread($request, true);
        $layout = $this->breadmanager->getLayoutForAction($bread, 'edit');

        $data = $bread->getModel()->findOrFail($id);
        $pk = $id;

        if (!empty($layout->options->scope)) {
            $data = $bread->getModel()->{$layout->options->scope}()->findOrFail($id);
        }

        if ($bread->usesTranslatableTrait()) {
            $data->dontTranslate();
        }

        $relationships = $bread->relationships->values();

        $data = (object) json_decode($data->toJson());
        $breadData = (object)[];

        $layout->formfields->each(function ($formfield) use ($data, &$breadData) {
            $value = $data->{$formfield->column->column} ?? null;

            if ($formfield->translatable ?? false) {
                $translations = [];
                $value = json_decode($value) ?? [];
                foreach ($value as $locale => $translated) {
                    $translations[$locale] = $formfield->edit($translated);
                }
                $breadData->{$formfield->column->column} = $translations;
            } else {
                $breadData->{$formfield->column->column} = $formfield->edit($value);
            }
        });

        return $this->inertiaRender('Bread/EditAdd', __('voyager::generic.edit_type', ['type' => $bread->name_singular]), [
            'bread'         => $bread,
            'action'        => 'edit',
            'layout'        => $layout,
            'relationships' => $relationships,
            'input'         => $breadData,
            'prev-url'      => url()->previous(),
            'primary-key'   => $pk,
        ]);
    }

    public function update(Request $request, mixed $id): Response|JsonResponse
    {
        $bread = $this->getBread($request, true);
        $layout = $this->breadmanager->getLayoutForAction($bread, 'edit');

        $model = $bread->getModel()->findOrFail($id);
        if (!empty($layout->options->scope)) {
            $model = $bread->getModel()->{$layout->options->scope}()->findOrFail($id);
        }
        $data = $request->get('data', []);

        if ($bread->usesTranslatableTrait()) {
            $model->dontTranslate();
        }

        // Validate Data
        $validate_all_locales = $layout->options->validate_locales == 'all' ?? false;
        $validation_errors = $this->validateData($layout->formfields, $data, $validate_all_locales);
        if (count($validation_errors) > 0) {
            return response()->json($validation_errors, 422);
        }
        $model = $this->updateStoreData($layout->formfields, $data, $model);

        if ($model->save()) {
            $layout->formfields->each(function ($formfield) use ($data, $model) {
                $formfield->updated($model, $data[$formfield->column->column]);
            });

            return response($model->getKey(), 200);
        } else {
            return response($model->getKey(), 500);
        }
    }

    public function delete(Request $request): array
    {
        $bread = $this->getBread($request, true);
        $model = $bread->getModel();

        $deleted = 0;

        if ($request->has('primary')) {
            $ids = $request->get('primary');
            if (!is_array($ids)) {
                $ids = [$ids];
            }
            $model->find($ids)->each(function ($entry) use (&$deleted) {
                $this->authorize('delete', $entry);
                $entry->delete();
                $deleted++;
            });
        }

        return [
            'amount' => $deleted,
        ];
    }

    public function restore(Request $request): ?array
    {
        // TODO: Check if layout allows usage of soft-deletes
        $bread = $this->getBread($request);
        if (!$bread->usesSoftDeletes()) {
            return null;
        }

        $restored = 0;

        $model = $bread->getModel()->withTrashed();

        if ($request->has('primary')) {
            $ids = $request->get('primary');
            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $model->find($ids)->each(function ($entry) use (&$restored) {
                if ($entry->trashed()) {
                    $this->authorize('restore', $entry);
                    $entry->restore();
                    $restored++;
                }
            });
        }

        return [
            'amount' => $restored,
        ];
    }

    public function order(Request $request): Response
    {
        $key = $request->get('key', null);
        $up = $request->get('up', true);

        if (!is_null($key)) {
            $bread = $this->getBread($request);
            $model = $bread->getModel();

            $move_item = $model->findOrFail($key);
            $current_order = $move_item->{$bread->order_field};

            $next_item = $model->where($bread->order_field, ($up ? $current_order - 1 : $current_order + 1))->first();
            if ($next_item) {
                $next_item->{$bread->order_field} = $current_order;
                $next_item->save();

                $move_item->{$bread->order_field} = ($up ? $current_order - 1 : $current_order + 1);
                $move_item->save();
            }
        }

        return response('', 200);
    }

    public function relationship(Request $request): array
    {
        // TODO: Validate that the method exists in edit/add layout
        $bread = $this->getBread($request, true);
        list($perPage, $query, $method, $column) = array_values($request->only(['perPage', 'query', 'method', 'column', 'key']));
        $translatable = $computed = false;

        if (Str::startsWith($column, 'computed.')) {
            $computed = true;
        }
        
        $relationship = $bread->relationships->where('method', $method)->first();

        if (!$relationship) {
            throw new \Exception('Relationship "'+$method+'" does not exist');
        }

        $data = $bread->getModel()->{$method}()->getRelated();
        $relatedTable = $data->getTable();
        $relatedKey = $data->getKeyName();

        $selected = collect();
        $model = $bread->getModel()->find($request->get('key', null));

        // Test if field is translatable
        $traits = class_uses($data);
        if ($traits !== false && in_array(Translatable::class, $traits) && in_array($column, $data->translatable)) {
            $translatable = true;
        }

        if ($computed) {
            list(, $accessor) = explode('.', $column);
            if ($model) {
                $selected = $model->{$method}->transform(function ($item) use ($column, $accessor) {
                    $item->{$column} = $item->{$accessor};

                    return $item;
                })->mapWithKeys(function ($item) use ($column, $relatedKey) {
                    return [$item->{$relatedKey} => $item->{$column}];
                });
            }

            $data = $data->get()->transform(function ($item) use ($column, $accessor) {
                $item->{$column} = $item->{$accessor};

                return $item;
            })->filter(function ($item) use ($query, $column) {
                if (!empty($query)) {
                    if (is_string($item->{$column})) {
                        return Str::contains(strtolower($item->{$column}), strtolower($query));
                    }
                }

                return true;
            })->sortBy(function ($item) use ($column) {
                return $item->{$column};
            });
        } else {
            if ($model) {
                $selected = $model->{$method}()->selectRaw("$relatedTable.$relatedKey, $relatedTable.$column")->pluck($column, $relatedKey);
            }

            if (!empty($query)) {
                if ($translatable) {
                    $data = $data->where(DB::raw('lower('.$column.'->"$.'.VoyagerFacade::getLocale().'")'), 'LIKE', '%'.$query.'%');
                } else {
                    $data = $data->where(DB::raw('lower('.$column.')'), 'LIKE', '%'.$query.'%');
                }
            }
        }

        $count = $data->count();

        if ($computed) {
            $data = $data->skip(($request->get('page', 1) - 1) * $perPage)->take($perPage);
        } else {
            $data = $data->orderBy($column)->skip(($request->get('page', 1) - 1) * $perPage)->take($perPage)->get();
        }

        $selected = $selected->transform(function ($value, $key) use ($translatable) {
            return [
                'key' => $key,
                'value' => $translatable ? VoyagerFacade::translate($value) : $value
            ];
        });

        $data->transform(function ($item) use ($column, $translatable) {
            return [
                'key'       => $item->getKey(),
                'value'     => $translatable ? VoyagerFacade::translate($item->{$column}) : $item->{$column},
            ];
        });

        return [
            'pages'     => ceil($count / $perPage),
            'data'      => $data->values(),
            'selected'  => $selected->values()
        ];
    }
}
