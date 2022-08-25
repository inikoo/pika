<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 26 Aug 2022 00:19:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\WithUpdate;
use App\Models\HumanResources\Employee;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Employee $employee
 */
class SetEmployeePhoto
{
    use AsAction;
    use WithUpdate;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Employee $employee, string $image_path, string $filename): ActionResult
    {
        $res = new ActionResult();

        $checksum = md5_file($image_path);

        if ($employee->getMedia('photo', ['checksum' => $checksum])->count() == 0) {
            $employee->addMedia($image_path)
                ->preservingOriginal()
                ->withCustomProperties(['checksum' => $checksum])
                ->usingName($filename)
                ->usingFileName($checksum.".".pathinfo($image_path, PATHINFO_EXTENSION))
                ->toMediaCollection('photo');

            $res->changes = array_merge($res->changes, $employee->getChanges());
        }

        $res->model    = $employee;
        $res->model_id = $employee->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }


}
