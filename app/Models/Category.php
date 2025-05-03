<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\CategoryQueryBuilder;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $subsite_id
 */
final class Category extends BaseModel
{
    use HasFactory;
    use Sluggable;
    use SoftDeletes;

    // Properties

    protected $fillable = [
        'name',
        'subsite_id',
    ];

    public function sluggable(): array
    {
        return $this->getSlugFrom('name');
    }

    // Builders
    /*
        public function newEloquentBuilder($query): CategoryQueryBuilder
        {
            return new CategoryQueryBuilder($query);
        }
    */
    // Relationships

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            related: Category::class,
            foreignKey: 'parent_id',
        );
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(
            related: Category::class,
            foreignKey: 'parent_id',
        )->orderBy('name');
    }
}
