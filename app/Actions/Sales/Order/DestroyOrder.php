<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 19:49:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Order;

use App\Models\Sales\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class DestroyOrder
{
    use AsAction;

    public function handle(Order $order): Void
    {
        $order->transactions()->forceDelete();
    }
}
