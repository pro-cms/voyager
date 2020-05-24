<?php

namespace Voyager\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;
use Voyager\Admin\Manager\Breads as BreadManager;
use Voyager\Admin\Manager\Plugins as PluginManager;
use Voyager\Admin\Manager\Settings as SettingManager;
use Voyager\Admin\Traits\Bread\Browsable;

class VoyagerController extends Controller
{
    use Browsable;

    protected $breadmanager;
    protected $pluginmanager;
    protected $settingmanager;

    public function __construct(BreadManager $breadmanager, PluginManager $pluginmanager, SettingManager $settingmanager)
    {
        $this->breadmanager = $breadmanager;
        $this->pluginmanager = $pluginmanager;
        $this->settingmanager = $settingmanager;
    }

    private $mime_extensions = [
        'js'    => 'text/javascript',
        'css'   => 'text/css',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
    ];

    public function assets(Request $request)
    {
        $path = str_replace('/', DIRECTORY_SEPARATOR, Str::start(urldecode($request->path), '/'));
        $path = realpath(dirname(__DIR__, 3).'/resources/assets/dist').$path;

        if (realpath($path) != $path) {
            abort(404);
        }

        if (File::exists($path)) {
            $extension = Str::afterLast($path, '.');
            $mime = $this->mime_extensions[$extension] ?? File::mimeType($path);

            $response = response(File::get($path), 200, ['Content-Type' => $mime]);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));

            return $response;
        }

        abort(404);
    }

    // Return all necessary data to run the Voyager SPA
    public function data()
    {
        return response()->json([
            'backups'       => $this->breadmanager->getBackups(),
            'breads'        => $this->breadmanager->getBreads(),
            'localization'  => VoyagerFacade::getLocalization(),
            'formfields'    => $this->breadmanager->getFormfields(),
            'routes'        => VoyagerFacade::getRoutes(),
            'search_title'  => $this->breadmanager->getBreadSearchPlaceholder(),
            'settings'      => $this->settingmanager->getSettings(),
            'tables'        => VoyagerFacade::getTables(),
            'user'          => VoyagerFacade::auth()->user(),
            'user_name'     => VoyagerFacade::auth()->name(),
        ]);
    }

    // Search all BREADS
    public function globalSearch(Request $request)
    {
        $q = $request->get('query');
        $breads = $this->breadmanager->getBreads();
        $results = collect([]);

        $this->breadmanager->getBreads()->each(function ($bread) use ($q, &$results) {
            if ($bread->global_search_field !== '') {
                $layout = $this->getLayoutForAction($bread, 'browse');
                if ($layout) {
                    $query = $bread->getModel()->select('*');
                    $query = $this->globalSearchQuery($q, $layout, VoyagerFacade::getLocale(), $query);
                    $bread_results = $query->get();
                    if (count($bread_results) > 0) {
                        $results[$bread->table] = [
                            'count'     => count($bread_results),
                            'results'   => $bread_results->take(3)->mapWithKeys(function ($result) use ($bread) {
                                return [$result->getKey() => $result->{$bread->global_search_field}];
                            }),
                        ];
                    }
                }
            }
        });

        return $results;
    }

    public function getDisks(Request $request)
    {
        $disks = collect(array_keys(config('filesystems.disks', [])))->mapWithKeys(function ($disk) {
            return [$disk => $disk];
        });
        return [
            $disks
        ];
    }
}
