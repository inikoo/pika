<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Aug 2022 22:01:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Actions\HumanResources\Employee;


use App\Actions\StoreModelAction;
use App\Models\Organisations\Organisation;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreEmployee extends StoreModelAction
{
    use AsAction;

    public function handle(Organisation $organisation, array $modelData): ActionResult
    {
        $employee = $organisation->employees()->create($modelData);

        return $this->finalise($employee);
    }


}
