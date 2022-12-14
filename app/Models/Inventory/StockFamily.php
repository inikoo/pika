<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 24 Oct 2022 10:03:07 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Inventory;

use App\Actions\Central\Tenant\HydrateTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * App\Models\Inventory\StockFamily
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string|null $state
 * @property string|null $name
 * @property string|null $description
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $source_id
 * @property-read \App\Models\Inventory\StockFamilyStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\Stock[] $stocks
 * @property-read int|null $stocks_count
 * @method static Builder|StockFamily newModelQuery()
 * @method static Builder|StockFamily newQuery()
 * @method static \Illuminate\Database\Query\Builder|StockFamily onlyTrashed()
 * @method static Builder|StockFamily query()
 * @method static Builder|StockFamily whereCode($value)
 * @method static Builder|StockFamily whereCreatedAt($value)
 * @method static Builder|StockFamily whereData($value)
 * @method static Builder|StockFamily whereDeletedAt($value)
 * @method static Builder|StockFamily whereDescription($value)
 * @method static Builder|StockFamily whereId($value)
 * @method static Builder|StockFamily whereName($value)
 * @method static Builder|StockFamily whereSlug($value)
 * @method static Builder|StockFamily whereSourceId($value)
 * @method static Builder|StockFamily whereState($value)
 * @method static Builder|StockFamily whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|StockFamily withTrashed()
 * @method static \Illuminate\Database\Query\Builder|StockFamily withoutTrashed()
 * @mixin \Eloquent
 */
class StockFamily extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $casts = [
        'data'       => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function () {
                HydrateTenant::make()->inventoryStats();
            }
        );
        static::deleted(
            function () {
                HydrateTenant::make()->inventoryStats();
            }
        );
        static::updated(function (StockFamily $stockFamily) {
            if ($stockFamily->wasChanged('state')) {
                HydrateTenant::make()->inventoryStats();
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->slugsShouldBeNoLongerThan(32)
            ->saveSlugsTo('slug');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(StockFamilyStats::class);
    }


}
