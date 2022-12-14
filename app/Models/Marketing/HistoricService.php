<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 06 Dec 2022 16:43:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Marketing\HistoricService
 *
 * @property int $id
 * @property string $slug
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $service_id
 * @property string $price unit price
 * @property string|null $code
 * @property string|null $name
 * @property int|null $source_id
 * @property-read \App\Models\Marketing\Service $product
 * @property-read \App\Models\Marketing\HistoricServiceStats|null $stats
 * @method static Builder|HistoricService newModelQuery()
 * @method static Builder|HistoricService newQuery()
 * @method static \Illuminate\Database\Query\Builder|HistoricService onlyTrashed()
 * @method static Builder|HistoricService query()
 * @method static Builder|HistoricService whereCode($value)
 * @method static Builder|HistoricService whereCreatedAt($value)
 * @method static Builder|HistoricService whereDeletedAt($value)
 * @method static Builder|HistoricService whereId($value)
 * @method static Builder|HistoricService whereName($value)
 * @method static Builder|HistoricService wherePrice($value)
 * @method static Builder|HistoricService whereServiceId($value)
 * @method static Builder|HistoricService whereSlug($value)
 * @method static Builder|HistoricService whereSourceId($value)
 * @method static Builder|HistoricService whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|HistoricService withTrashed()
 * @method static \Illuminate\Database\Query\Builder|HistoricService withoutTrashed()
 * @mixin \Eloquent
 */
class HistoricService extends Model
{
    use SoftDeletes;
    use HasSlug;

    protected $casts = [
        'status' => 'boolean',

    ];

    public $timestamps = ["created_at"];
    public const UPDATED_AT = null;

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(HistoricServiceStats::class);
    }
}
