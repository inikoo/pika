<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 30 Aug 2022 12:46:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Actions\Inventory\Location;

use App\Models\Inventory\Location;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreLocation
{
    use AsAction;

    public function handle(WarehouseArea|Warehouse $parent, array $modelData):Location
    {

        if (class_basename($parent::class) == 'WarehouseArea') {
            $modelData['warehouse_id'] = $parent->warehouse_id;
        }
        /** @var Location $location */
        $location = $parent->locations()->create($modelData);
        $location->stats()->create();

        return $location;
    }
}
