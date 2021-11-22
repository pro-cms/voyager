<?php

namespace Voyager\Admin\Http\Controllers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Voyager\Admin\Classes\Bread;
use Voyager\Admin\Events\Builder\BackedUp as BackedUpEvent;
use Voyager\Admin\Events\Builder\Created as CreatedEvent;
use Voyager\Admin\Events\Builder\Deleted as DeletedEvent;
use Voyager\Admin\Events\Builder\Restored as RestoredEvent;
use Voyager\Admin\Events\Builder\Updated as UpdatedEvent;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;
use Voyager\Admin\Manager\Breads as BreadManager;
use Voyager\Admin\Rules\ClassExists as ClassExistsRule;
use Voyager\Admin\Rules\DefaultLocale as DefaultLocaleRule;

class BreadBuilderController extends Controller
{
    public function __construct(protected BreadManager $breadmanager)
    {
        parent::__construct();
    }

    /**
     * Display all BREADs.
     *
     * @return InertiaResponse
     */
    public function index(): InertiaResponse
    {
        $this->authorize('browse', new Bread(''));

        return $this->inertiaRender('Builder/Browse', __('voyager::generic.breads'), [
            'tables'  => VoyagerFacade::getTables(),
            'breads'  => $this->breadmanager->getBreads()->values(),
            'backups' => $this->breadmanager->getBackups(),
        ]);
    }

    /**
     * Create a BREAD for a given $table.
     *
     * @param string $table
     *
     * @return InertiaResponse|\Illuminate\Http\RedirectResponse
     */
    public function create($table): InertiaResponse|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('add', [Bread::class, $table]);
        if (!in_array($table, VoyagerFacade::getTables())) {
            throw new \Voyager\Admin\Exceptions\TableNotFoundException('Table "'.$table.'" does not exist');
        }

        if ($this->breadmanager->getBread($table)) {
            VoyagerFacade::flashMessage(
                __('voyager::builder.bread_already_exists', ['table' => $table]),
                'yellow',
                5000,
                true
            );

            return redirect()->route('voyager.bread.edit', $table);
        }

        return $this->inertiaRender('Builder/EditAdd', __('voyager::generic.add_type', ['type' => __('voyager::generic.bread')]), [
            'data'   => $this->breadmanager->createBread($table),
            'is-new' => true,
        ]);
    }

    /**
     * Edit a BREAD for a given $table.
     *
     * @param string $table
     *
     * @return InertiaResponse|\Illuminate\Http\RedirectResponse
     */
    public function edit($table): InertiaResponse|\Illuminate\Http\RedirectResponse
    {
        $bread = $this->breadmanager->getBread($table);
        if (!$bread) {
            VoyagerFacade::flashMessage(__('voyager::builder.bread_does_no_exist', [
                'table' => $table
            ]), 'red', 5000, true);

            return redirect()->route('voyager.bread.create', $table);
        }

        $this->authorize('edit', $bread);

        return $this->inertiaRender('Builder/EditAdd', __('voyager::generic.edit_type', ['type' => __('voyager::generic.bread')]), [
            'data'   => $bread,
            'is-new' => false,
        ]);
    }

    /**
     * Update a BREAD.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $table
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $table)
    {
        $bread = $request->bread;
        $exists = $this->breadmanager->getBread($table);

        if ($exists) {
            $this->authorize('edit', $bread);
        } else {
            $this->authorize('add', [Bread::class, $table]);
        }

        if (!is_array($bread)) {
            return response()->json([__('voyager::bread.json_data_not_valid')], 422);
        }

        $bread = (object) $bread;

        $bread->table = $table;

        $validator = Validator::make($request->get('bread'), [
            'slug'          => ['required', new DefaultLocaleRule()],
            'name_singular' => ['required', new DefaultLocaleRule()],
            'name_plural'   => ['required', new DefaultLocaleRule()],
            'model'         => ['nullable', new ClassExistsRule()],
            'controller'    => ['nullable', new ClassExistsRule()],
            'policy'        => ['nullable', new ClassExistsRule()],
        ]);

        if ($validator->passes()) {
            if (!$this->breadmanager->storeBread($bread)) {
                $validator->errors()->add('bread', __('voyager::builder.bread_save_failed')); // @phpstan-ignore-line

                return response()->json($validator->errors(), 422);
            }
            $bread = $this->breadmanager->getBread($table);
            if ($bread !== null) {
                if ($exists) {
                    event(new UpdatedEvent($bread));
                } else {
                    event(new CreatedEvent($bread));
                }
            }
        } else {
            return response()->json($validator->errors(), 422);
        }

        return response()->json($validator->messages(), 200);
    }

    /**
     * Remove a BREAD by a given table.
     *
     * @param string $table
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($table)
    {
        $bread = $this->breadmanager->getBread($table);
        $this->authorize('delete', $bread);
        if (is_null($bread)) {
            return response('', 500);
        }
        event(new DeletedEvent($bread));

        return response('', $this->breadmanager->deleteBread($table) ? 200 : 500);
    }

    /**
     * Get BREAD properties (accessors, scopes and relationships) for a model.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProperties(Request $request)
    {
        $table = $request->get('table', null);

        if ($this->breadmanager->getBread($table)) {
            $this->authorize('edit', $this->breadmanager->getBread($table));
        } else {
            $this->authorize('add', [Bread::class, $table]);
        }

        $model = $request->get('model', null);

        $validator = Validator::make(['model' => $model], [
            'model'         => ['required', new ClassExistsRule()],
        ]);

        if (!$validator->passes()) {
            return response()->json($validator->errors(), 422);
        }
        
        $instance = new $model();
        $reflection = $this->breadmanager->getModelReflectionClass($model);
        $resolve_relationships = $request->get('resolve_relationships', true);

        $softdeletes = false;
        $traits = class_uses($instance);
        if ($traits !== false && in_array(SoftDeletes::class, $traits)) {
            $softdeletes = true;
        }

        return response()->json([
            'columns'       => VoyagerFacade::getColumns($instance->getTable()),
            'computed'      => $this->breadmanager->getModelComputedProperties($reflection)->values(),
            'scopes'        => $this->breadmanager->getModelScopes($reflection)->values(),
            'relationships' => $this->breadmanager->getModelRelationships($reflection, $instance, $resolve_relationships)->values(),
            'softdeletes'   => $softdeletes,
        ], 200);
    }

    /**
     * Create a model for a table.
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function createModel(Request $request)
    {
        $name = Str::singular(Str::studly($request->get('table', null)));

        $namespace = Str::start(Str::finish(Container::getInstance()->getNamespace() ?? 'App\\', '\\'), '\\'); // @phpstan-ignore-line
        $class = (is_dir(app_path('Models')) ? $namespace.'Models\\' : $namespace).$name;

        if (class_exists($class)) {
            return response()->json([
                'exists'    => true,
                'class'     => $class,
            ], 200);
        } else {
            $res = Artisan::call('voyager:model', [
                'name'  => $name,
            ]);

            if ($res === 0) {
                return response()->json([
                    'exists'    => false,
                    'class'     => $class,
                ], 200);
            }
        }

        return response()->json([
            'message'   => __('voyager::build.could_not_generate_model', ['model' => $class]),
        ], 500);
    }

    /**
     * Backup a BREAD.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function backupBread(Request $request)
    {
        $table = $request->get('table', '');
        $result = $this->breadmanager->backupBread($table);
        $bread = $this->breadmanager->getBread($table);
        $this->authorize('backup', $bread);
        if ($bread !== null) {
            event(new BackedUpEvent($bread));
        }

        if (is_bool($result)) {
            return response(null, 500);
        }

        return response($result, 200);
    }

    /**
     * Rollback a BREAD.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function rollbackBread(Request $request)
    {
        $table = $request->get('table', '');
        $result = $this->breadmanager->rollbackBread($table, $request->get('path', ''));
        $bread = $this->breadmanager->getBread($table);
        $this->authorize('restore', $bread);
        if ($bread !== null) {
            event(new RestoredEvent($bread));
        }

        if (is_bool($result)) {
            return response(null, 500);
        }

        return response($result, 200);
    }
}
