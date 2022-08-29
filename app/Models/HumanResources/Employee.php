<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Aug 2022 14:10:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Models\HumanResources;

use App\Models\Organisations\Organisation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\HumanResources\Employee
 *
 * @property int $id
 * @property int $organisation_id
 * @property string $nickname
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $gender
 * @property string|null $worker_number
 * @property string|null $job_title
 * @property string $type
 * @property string $state
 * @property string|null $employment_start_at
 * @property string|null $employment_end_at
 * @property string|null $emergency_contact
 * @property array|null $salary
 * @property array|null $working_hours
 * @property array $data
 * @property array $job_position_scopes
 * @property array $errors
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $organisation_source_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HumanResources\JobPosition[] $jobPositions
 * @property-read int|null $job_positions_count
 * @method static Builder|Employee newModelQuery()
 * @method static Builder|Employee newQuery()
 * @method static Builder|Employee query()
 * @method static Builder|Employee whereCreatedAt($value)
 * @method static Builder|Employee whereData($value)
 * @method static Builder|Employee whereDateOfBirth($value)
 * @method static Builder|Employee whereDeletedAt($value)
 * @method static Builder|Employee whereEmail($value)
 * @method static Builder|Employee whereEmergencyContact($value)
 * @method static Builder|Employee whereEmploymentEndAt($value)
 * @method static Builder|Employee whereEmploymentStartAt($value)
 * @method static Builder|Employee whereErrors($value)
 * @method static Builder|Employee whereGender($value)
 * @method static Builder|Employee whereId($value)
 * @method static Builder|Employee whereIdentityDocumentNumber($value)
 * @method static Builder|Employee whereIdentityDocumentType($value)
 * @method static Builder|Employee whereJobPositionScopes($value)
 * @method static Builder|Employee whereJobTitle($value)
 * @method static Builder|Employee whereName($value)
 * @method static Builder|Employee whereNickname($value)
 * @method static Builder|Employee whereOrganisationId($value)
 * @method static Builder|Employee whereOrganisationSourceId($value)
 * @method static Builder|Employee wherePhone($value)
 * @method static Builder|Employee whereSalary($value)
 * @method static Builder|Employee whereState($value)
 * @method static Builder|Employee whereType($value)
 * @method static Builder|Employee whereUpdatedAt($value)
 * @method static Builder|Employee whereWorkerNumber($value)
 * @method static Builder|Employee whereWorkingHours($value)
 * @mixin \Eloquent
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read Organisation|null $organisation
 */
class Employee extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        'data'                => 'array',
        'errors'              => 'array',
        'salary'              => 'array',
        'working_hours'       => 'array',
        'job_position_scopes' => 'array',
        'date_of_birth'       => 'datetime:Y-m-d',

    ];

    protected $attributes = [
        'data'                => '{}',
        'errors'              => '{}',
        'salary'              => '{}',
        'working_hours'       => '{}',
        'job_position_scopes' => '{}',
    ];

    protected $guarded = [];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->useDisk('local')
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(256)
                    ->height(256);
            });
    }

    public function jobPositions(): BelongsToMany
    {
        return $this->belongsToMany(JobPosition::class)->withTimestamps();
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

}