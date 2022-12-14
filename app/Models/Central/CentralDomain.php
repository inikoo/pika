<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Oct 2022 19:15:11 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Central\CentralDomain
 *
 * @property int $id
 * @property string $slug
 * @property string $tenant_id
 * @property int $website_id
 * @property string $domain
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Central\AdminUser|null $adminUser
 * @property-read \App\Models\Central\CentralDomainStats|null $stats
 * @property-read \App\Models\Central\Tenant $tenant
 * @method static Builder|CentralDomain newModelQuery()
 * @method static Builder|CentralDomain newQuery()
 * @method static \Illuminate\Database\Query\Builder|CentralDomain onlyTrashed()
 * @method static Builder|CentralDomain query()
 * @method static Builder|CentralDomain whereCreatedAt($value)
 * @method static Builder|CentralDomain whereDeletedAt($value)
 * @method static Builder|CentralDomain whereDomain($value)
 * @method static Builder|CentralDomain whereId($value)
 * @method static Builder|CentralDomain whereSlug($value)
 * @method static Builder|CentralDomain whereState($value)
 * @method static Builder|CentralDomain whereTenantId($value)
 * @method static Builder|CentralDomain whereUpdatedAt($value)
 * @method static Builder|CentralDomain whereWebsiteId($value)
 * @method static \Illuminate\Database\Query\Builder|CentralDomain withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CentralDomain withoutTrashed()
 * @mixin \Eloquent
 */
class CentralDomain extends Model
{
    use HasSlug;
    use SoftDeletes;


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug');
    }


    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(CentralDomainStats::class);
    }

    protected $guarded = [];

    public function adminUser(): MorphOne
    {
        return $this->morphOne(AdminUser::class, 'userable');
    }

}
