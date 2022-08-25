<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 26 Aug 2022 01:35:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Actions\Marketing\Shop;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Marketing\Shop;
use App\Models\Organisations\Organisation;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreShop
{
    use AsAction;

    public function handle(Organisation $organisation,array $modelData, array $addressData = []): ActionResult
    {
        $res  = new ActionResult();
        /** @var Shop $shop */
        $shop = $organisation->shops()->create($modelData);
        $shop->stats()->create();

        $address = StoreAddress::run($addressData);

        $shop->address_id = $address->id;
        $shop->location   = $shop->getLocation();
        $shop->save();

        $res->model    = $shop;
        $res->model_id = $shop->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}