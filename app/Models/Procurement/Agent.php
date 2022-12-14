<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 26 Oct 2022 09:53:53 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Procurement;

use App\Actions\Central\Tenant\HydrateTenant;
use App\Models\Helpers\Address;
use App\Models\Traits\HasAddress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\Tenancy\Database\Concerns\TenantConnection;


/**
 * App\Models\Procurement\Agent
 *
 * @property int $id
 * @property string $type
 * @property bool $status
 * @property string $slug
 * @property string $code
 * @property string $owner_type
 * @property int $owner_id
 * @property string $name
 * @property string|null $company_name
 * @property string|null $contact_name
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $address_id
 * @property array $location
 * @property int $currency_id
 * @property array $settings
 * @property array $shared_data
 * @property array $tenant_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $global_id
 * @property int|null $source_id
 * @property int|null $source_agent_id
 * @property-read Address|null $address
 * @property-read \Illuminate\Database\Eloquent\Collection|Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read string $formatted_address
 * @property-read Model|\Eloquent $owner
 * @property-read \App\Models\Procurement\AgentStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Procurement\Supplier[] $suppliers
 * @property-read int|null $suppliers_count
 * @method static Builder|Agent newModelQuery()
 * @method static Builder|Agent newQuery()
 * @method static \Illuminate\Database\Query\Builder|Agent onlyTrashed()
 * @method static Builder|Agent query()
 * @method static Builder|Agent whereAddressId($value)
 * @method static Builder|Agent whereCode($value)
 * @method static Builder|Agent whereCompanyName($value)
 * @method static Builder|Agent whereContactName($value)
 * @method static Builder|Agent whereCreatedAt($value)
 * @method static Builder|Agent whereCurrencyId($value)
 * @method static Builder|Agent whereDeletedAt($value)
 * @method static Builder|Agent whereEmail($value)
 * @method static Builder|Agent whereGlobalId($value)
 * @method static Builder|Agent whereId($value)
 * @method static Builder|Agent whereLocation($value)
 * @method static Builder|Agent whereName($value)
 * @method static Builder|Agent whereOwnerId($value)
 * @method static Builder|Agent whereOwnerType($value)
 * @method static Builder|Agent wherePhone($value)
 * @method static Builder|Agent whereSettings($value)
 * @method static Builder|Agent whereSharedData($value)
 * @method static Builder|Agent whereSlug($value)
 * @method static Builder|Agent whereSourceAgentId($value)
 * @method static Builder|Agent whereSourceId($value)
 * @method static Builder|Agent whereStatus($value)
 * @method static Builder|Agent whereTenantData($value)
 * @method static Builder|Agent whereType($value)
 * @method static Builder|Agent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Agent withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Agent withoutTrashed()
 * @mixin \Eloquent
 */
class Agent extends Model
{
    use SoftDeletes;
    use HasAddress;
    use HasSlug;
    use TenantConnection;

    protected $table = 'suppliers';

    protected $casts = [
        'shared_data' => 'array',
        'tenant_data' => 'array',
        'settings'    => 'array',
        'location'    => 'array',
        'status'      => 'boolean',
    ];

    protected $attributes = [
        'shared_data' => '{}',
        'tenant_data' => '{}',
        'settings'    => '{}',
        'location'    => '{}',

    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function () {
                HydrateTenant::make()->procurementStats();
            }
        );
        static::deleted(
            function () {
                HydrateTenant::make()->procurementStats();
            }
        );

        static::updated(function (Agent $agent) {
            if (!$agent->wasRecentlyCreated) {
                if ($agent->wasChanged('status')) {
                    HydrateTenant::make()->procurementStats();
                }
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(AgentStats::class);
    }

    public function addresses(): MorphToMany
    {
        return $this->morphToMany(Address::class, 'addressable')->withTimestamps();
    }

    public function suppliers(): MorphMany
    {
        return $this->morphMany(Supplier::class, 'owner');
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

}
