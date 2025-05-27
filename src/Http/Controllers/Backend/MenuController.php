<?php

namespace AcitJazz\MainMenu\Http\Controllers\Backend;

use AcitJazz\MainMenu\Http\Requests\MenuRequest;
use AcitJazz\MainMenu\Http\Resources\Backend\ListModelResource;
use Illuminate\Routing\Controller;
use Facades\App\Http\Repositories\PageRepository;
use Facades\AcitJazz\MainMenu\Http\Repositories\MenuRepository;
use AcitJazz\MainMenu\Http\Resources\Backend\MenuResource;
use AcitJazz\MainMenu\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
 
class MenuController extends Controller
{
 
    public function index()
    {
        $locations = [
            'Header',
            'Footer',
            'Site Map'
        ];
        $menus = MenuRepository::paginate(20);
        return Inertia::render('menu/index', [
            'menus' => MenuResource::collection($menus),
            'locations' => $locations,
            'title' => request('trash') ? 'Trash' : 'Menu',
            'trash' => request('trash') ? true : false,
            'request' => request()->all(),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => 'Menu',
                    'url' => route('menu.index'),
                ],
            ],
        ]);
    }

    public function getPage()
    {

        $pages = PageRepository::all();
        $pages = $pages->map(fn($page) => new ListModelResource($page, 'App\Models\Page'));
        return response()->json($pages);
    }

    /**
     * create view.
     */
    public function create()
    {
        $menus = Menu::where('location',request('location'))->with('children.children.children')->whereNull('parent_id')->get();
        $menu = new Menu();
        return Inertia::render('menu/form', [
            'menus' => MenuResource::collection($menus)->resolve(),
            'menu' => MenuResource::make($menu)->resolve(),
            'location' => request('location'),
            'title' => 'Edit '.'Menu',
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => 'Menu',
                    'url' => route('menu.index'),
                ],
            ],
        ]);
        return redirect()->route('menu.edit',['menu'=>$menu->id]);
    }

    /**
     * store data.
     */
    public function store(Request $request)
    {
        $menus = array_filter($request->menus, function ($menu) {
            return !is_null($menu['title']) || !empty($menu['title']);
        });

        foreach ($menus as $menu) {
            $this->saveMenuWithChildren($menu);
        }

        Cache::tags(['menus'])->flush();

        return redirect()->back()->with('message', 'Menu has been updated');
    }
    
    protected function saveMenuWithChildren(array $menu, $parentId = null)
    {
        // Normalize model jika perlu
        if (isset($menu['model']) && is_string($menu['model'])) {
            $decoded = json_decode($menu['model'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $menu['model'] = $decoded;
            }
        }

        if ($menu['type'] === '_self' && !empty($menu['model']) && is_array($menu['model'])) {
            $menu['model_id'] = $menu['model']['id'] ?? null;
            $menu['model'] = $menu['model']['model'] ?? null;
        }

        // Set parent_id jika diberikan
        $menu['parent_id'] = $parentId;

        // Simpan menu
        $savedMenu = Menu::updateOrCreate(
            ['id' => $menu['id']],
            $menu
        );

        // Simpan children secara rekursif
        if (!empty($menu['children']) && is_array($menu['children'])) {
            foreach ($menu['children'] as $child) {
                $this->saveMenuWithChildren($child, $savedMenu->id);
            }
        }
    }

    /**
     * Edit view.
     */
    public function edit(Menu $menu)
    {
        return Inertia::render('menu/form', [
            'menu' => MenuResource::make($menu)->resolve(),
            'type' => type(),
            'method' => 'update',
            'title' => 'Edit '.'Menu',
            'locale' => app()->getLocale(),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => 'Menu',
                    'url' => route('menu.index'),
                ],
            ],
        ]);
    }

    /**
     * Update Data.
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->update($request->all());
        // Sync tags
        if($request->tags){
            $menu->tags()->sync(collect($request->tags)->pluck('id')); // array of category_id
        }


        Cache::tags(['menus'])->flush();

        return redirect()->back()->with('message', toTitle($menu->title).' has been updated');
    }

    /**
     * Remove the specified resource from storage temporary.
     */
    public function delete($menu)
    {
       
        $menu = Menu::find($menu);
        $location = $menu->location;
        if (!$menu) {
            return abort(404);
        }
        $menu->delete();

        Cache::tags(['menus'])->flush();

        return redirect()->route('menu.create',['location' => $location])->with('message', toTitle($menu->title.' hase been deleted'));
    }

    /**
     * Remove data permanently.
     */
    public function destroy($menu)
    {
        $menu = Menu::withTrashed()->find($menu);
        if (!$menu) {
            return abort(404);
        }
        $menu->forceDelete();

        Cache::tags(['menus'])->flush();

        return redirect()->route('menu.index')->with('message', toTitle($menu->title.' hase been destroyed'));
    }

    public function destroyAll()
    {
        $ids = explode(',', request('selected'));
        $menus = Menu::whereIn('_id', $ids)->withTrashed()->get();
        foreach ($menus as $menu) {
            $menu->forceDelete();
        }
        Cache::tags(['menus'])->flush();

        return redirect()->route('menu.index')->with('message', toTitle($menu->title).' has been destroyed');
    }

    /**
     * Restore Data from trash.
     */
    public function restore($menu)
    {
        $menu = Menu::withTrashed()->find($menu);
        if (!$menu) {
            return abort(404);
        }
        $menu->restore();
        Cache::tags(['menus'])->flush();

        return redirect()->route('menu.index')->with('message', toTitle($menu->title).' has been restored');
    }
 
}