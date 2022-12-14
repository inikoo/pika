<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 06 Sept 2022 15:36:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\SysAdmin;

use App\Actions\Central\Tenant\HydrateTenant;
use App\Models\Central\CentralUser;
use App\Models\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stancl\Tenancy\Contracts\Syncable;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;


/**
 * App\Models\SysAdmin\User
 *
 * @property int $id
 * @property string $username
 * @property bool $status
 * @property string|null $parent_type
 * @property int|null $parent_id
 * @property string|null $email
 * @property string|null $about
 * @property string|null $remember_token
 * @property array $profile
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $password
 * @property int $number_tenants
 * @property string $global_id
 * @property-read string $avatar
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereAbout($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereGlobalId($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereNumberTenants($value)
 * @method static Builder|User whereParentId($value)
 * @method static Builder|User whereParentType($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereProfile($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereSettings($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia, Syncable
{
    use HasApiTokens, HasFactory, Notifiable;
    use InteractsWithMedia;
    use HasRoles;
    use ResourceSyncing;


    protected $guarded = [
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public string $global_id = 'global_id';

    protected $casts = [

        'profile'     => 'array',
        'settings' => 'array',
        'status'   => 'boolean'
    ];




    protected $attributes = [
        'profile'     => '{}',
        'settings' => '{}',
    ];


    public static function boot()
    {
        parent::boot();


        static::updated(function ($item) {
            if (!$item->wasRecentlyCreated) {
                if ($item->wasChanged('status')) {
                    HydrateTenant::make()->userStats();
                }
            }
        });
    }


    public function getGlobalIdentifierKey()
    {
        return $this->getAttribute($this->getGlobalIdentifierKeyName());
    }

    public function getGlobalIdentifierKeyName(): string
    {
        return $this->global_id;
    }

    public function getCentralModelName(): string
    {
        return CentralUser::class;
    }

    public function getSyncedAttributeNames(): array
    {
        return [
            'username',
            'password',
            'email',
            'about',
            'number_tenants'
        ];
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(256)
                    ->height(256);
            });
    }


    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function getAvatarAttribute(): string
    {
        $mediaItems = $this->getMedia('profile');
        if(count($mediaItems)>0){
            return  $mediaItems[0]->getUrl();
        }
        return '';
    }

}
