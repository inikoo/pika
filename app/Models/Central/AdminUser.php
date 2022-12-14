<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 22 Sept 2022 12:39:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Central;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\Tenancy\Database\Concerns\CentralConnection;


/**
 * App\Models\Central\AdminUser
 *
 * @property int $id
 * @property string $userable_type
 * @property int $userable_id
 * @property string $username
 * @property string $password
 * @property bool $status
 * @property int $language_id
 * @property int $timezone_id
 * @property string|null $email
 * @property array $data
 * @property array $settings
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Model|\Eloquent $tenant
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read Model|\Eloquent $userable
 * @method static Builder|AdminUser newModelQuery()
 * @method static Builder|AdminUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|AdminUser onlyTrashed()
 * @method static Builder|AdminUser query()
 * @method static Builder|AdminUser whereCreatedAt($value)
 * @method static Builder|AdminUser whereData($value)
 * @method static Builder|AdminUser whereDeletedAt($value)
 * @method static Builder|AdminUser whereEmail($value)
 * @method static Builder|AdminUser whereId($value)
 * @method static Builder|AdminUser whereLanguageId($value)
 * @method static Builder|AdminUser wherePassword($value)
 * @method static Builder|AdminUser whereRememberToken($value)
 * @method static Builder|AdminUser whereSettings($value)
 * @method static Builder|AdminUser whereStatus($value)
 * @method static Builder|AdminUser whereTimezoneId($value)
 * @method static Builder|AdminUser whereTwoFactorRecoveryCodes($value)
 * @method static Builder|AdminUser whereTwoFactorSecret($value)
 * @method static Builder|AdminUser whereUpdatedAt($value)
 * @method static Builder|AdminUser whereUserableId($value)
 * @method static Builder|AdminUser whereUserableType($value)
 * @method static Builder|AdminUser whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|AdminUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AdminUser withoutTrashed()
 * @mixin Eloquent
 */
class AdminUser extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use SoftDeletes;
    use CentralConnection;
    use HasSlug;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'status'   => 'boolean'
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $hidden = [
        'password',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('username')
            ->saveSlugsTo('username');
    }

    /**
     * @return string
     * Hack for laravel permissions to work
     */
    public function guardName(): string
    {
        return 'admin';
    }

    protected $guarded = [];


    public function userable(): MorphTo
    {
        return $this->morphTo(null,null,null,'numeric_id');
    }

    public function tenant(): MorphTo
    {
        return $this->morphTo('userable','userable_type','userable_id','numeric_id');
    }

    public function getUserable(): Model|Eloquent
    {

        if($this->userable_type=='Tenant'){
            return $this->tenant;
        }else{
            return $this->userable;
        }

    }


}
