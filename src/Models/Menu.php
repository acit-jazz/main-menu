<?php

namespace AcitJazz\MainMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pipeline\Pipeline;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;

class Menu extends Model
{
    use HasSlug;

    protected $table = 'menus';

    protected $fillable = [
        'title',
        'slug',
        'model',
        'model_id',
        'style',
        'url',
        'type',       
        'location',    // e.g. header, footer, sidebar
        'parent_id',   // for nesting
        'order',       // for sorting
        'is_active',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get child menu items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public static function paginateWithFilters($limit)
    {
        return app(Pipeline::class)
            ->send(static::query()
                ->with(['children.children.children'])
                ->whereNull('parent_id'))
            ->through([
                \App\QueryFilters\SortBy::class,
                \App\QueryFilters\Type::class,
            ])
            ->thenReturn()
            ->paginate($limit)
            ->withQueryString();
    }

    public static function allWithFilters()
    {
        return app(Pipeline::class)
            ->send(static::query()
                ->with(['children.children.children'])
                ->whereNull('parent_id'))
            ->through([
                \App\QueryFilters\SortBy::class,
                \App\QueryFilters\Type::class,
            ])
            ->thenReturn()
            ->get();
    }
}
