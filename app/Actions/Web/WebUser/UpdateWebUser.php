<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 04 Nov 2022 15:54:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Web\WebUser;

use App\Actions\WithActionUpdate;
use App\Models\Web\WebUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;


class UpdateWebUser
{
    use WithActionUpdate;

    public function handle(WebUser $webUser, array $modelData): WebUser
    {

        if(Arr::exists($modelData,'password')){
            $modelData['password']=Hash::make($modelData['password']);
        }

        return $this->update($webUser, $modelData, ['data', 'settings']);
    }
}
