<?php

namespace AcitJazz\MainMenu\Http\Repositories;

use AcitJazz\MainMenu\Http\Resources\Frontend\FeMenuResource;
use AcitJazz\MainMenu\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MenuRepository
{
    public const CACHE_KEY = 'MENU';

    public function pluck($name, $id)
    {
        $key = "pluck.{$name}.{$id}";
        $cacheKey = $this->getCacheKey($key);

        return Cache::tags(['menus'])->remember($cacheKey, Carbon::now()->addMonths(6), function () use ($name, $id) {
            return Menu::pluck($name, $id);
        });
    }

    public function getByLocation($location = 'Header')
    {
        $keys = $this->requestValue();
        $key = "all.{$keys}.{$location}";
        $cacheKey = $this->getCacheKey($key);

        return Cache::tags(['menus'])->remember($cacheKey, Carbon::now()->addMonths(6), function ()  use ($location){
            return FeMenuResource::collection(Menu::where('location',$location)->with('children.children.children')->whereNull('parent_id')->get())->resolve();
        });
    }
    
    public function all()
    {
        $keys = $this->requestValue();
        $key = "all.{$keys}";
        $cacheKey = $this->getCacheKey($key);

        return Cache::tags(['menus'])->remember($cacheKey, Carbon::now()->addMonths(6), function () {
            return Menu::allWithFilters();
        });
    }

    public function findBySlug($slug)
    {
        $key = "findBySlug.{$slug}";
        $cacheKey = $this->getCacheKey($key);

        return Cache::tags(['menus'])->remember($cacheKey, Carbon::now()->addMonths(6), function () use ($slug) {
            return Menu::findBySlug($slug);
        });
    }

    public function findByTemplate($template)
    {
        $key = "findByTemplate.{$template}";
        $cacheKey = $this->getCacheKey($key);

        return Cache::tags(['menus'])->remember($cacheKey, Carbon::now()->addMonths(6), function () use ($template) {
            return Menu::where('template',$template)->latest('created_at')->first();
        });
    }

    public function paginate($number)
    {
        $keys = $this->requestValue();
        $key = "paginate.{$number}.{$keys}";
        $cacheKey = $this->getCacheKey($key);

        return Cache::tags(['menus'])->remember($cacheKey, Carbon::now()->addMonths(6), function () use ($number) {
            return Menu::paginateWithFilters($number);
        });
    }

    public function paginateTrash($number)
    {
        request()->merge(['trash' => '1']);
        $keys = $this->requestValue();
        $key = "paginateTrash.{$number}.{$keys}";
        $cacheKey = $this->getCacheKey($key);

        return Cache::tags(['menus'])->remember($cacheKey, Carbon::now()->addMonths(6), function () use ($number) {
            return Menu::paginateWithFilters($number);
        });
    }

    public function countTrash()
    {
        $key = 'countTrash';
        $cacheKey = $this->getCacheKey($key);

        return Cache::tags(['menus'])->remember($cacheKey, Carbon::now()->addMonths(6), function () {
            return Menu::onlyTrashed()->count();
        });
    }

    public function getCacheKey($key)
    {
        $key = strtoupper($key);

        return self::CACHE_KEY.".$key";
    }

    private function requestValue()
    {
        return http_build_query(request()->all(), '', '.');
    }
}
