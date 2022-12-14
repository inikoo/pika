<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 20 Sept 2022 22:43:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Central\CentralUser;

use App\Models\Central\CentralUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreCentralUser
{
    use AsAction;

    public function handle(array $modelData): CentralUser
    {
        $modelData['password']  = Hash::make($modelData['password']);
        $modelData['global_id'] = Str::uuid();

        return tenancy()->central(function () use ($modelData) {
            return CentralUser::create($modelData);
        });
    }
}
