<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Oct 2022 18:49:22 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Marketing;


use App\Actions\Marketing\Shop\HydrateShop;
use App\Models\Sales\SalesStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Marketing\Department
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property int|null $shop_id
 * @property string|null $state
 * @property string|null $name
 * @property string|null $description
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $source_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Marketing\Family[] $families
 * @property-read int|null $families_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Marketing\Product[] $products
 * @property-read int|null $products_count
 * @property-read SalesStats|null $salesStats
 * @property-read SalesStats|null $salesTenantCurrencyStats
 * @property-read \App\Models\Marketing\Shop|null $shop
 * @property-read \App\Models\Marketing\DepartmentStats|null $stats
 * @method static \Illuminate\Database\Eloquent\Builder|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department newQuery()
 * @method static Builder|Department onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedAt($value)
 * @method static Builder|Department withTrashed()
 * @method static Builder|Department withoutTrashed()
 * @mixin \Eloquent
 */
class Department extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected static function booted()
    {
        static::created(
            function (Department $department) {
                HydrateShop::make()->departmentsStats($department->shop);
            }
        );
        static::deleted(
            function (Department $department) {
                HydrateShop::make()->departmentsStats($department->shop);
            }
        );
        static::updated(function (Department $department) {
            if ($department->wasChanged('state')) {
                HydrateShop::make()->departmentsStats($department->shop);
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(DepartmentStats::class);
    }

    public function salesStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope', 'sales');
    }

    public function salesTenantCurrencyStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope', 'sales-tenant-currency');
    }

}
