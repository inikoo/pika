<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 22 Sept 2022 02:54:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


namespace App\Actions\SourceFetch\Aurora;


use App\Actions\Sales\Transaction\StoreTransaction;
use App\Models\Sales\Order;
use App\Models\Sales\Transaction;
use App\Services\Tenant\SourceTenantService;
use JetBrains\PhpStorm\NoReturn;
use Lorisleiva\Actions\Concerns\AsAction;


class FetchTransactions
{
    use AsAction;


    #[NoReturn] public function handle(SourceTenantService $tenantSource, int $source_id, Order $order): ?Transaction
    {
        if ($transactionData = $tenantSource->fetchTransaction(type: 'HistoricProduct', id: $source_id)) {
            if (!Transaction::where('source_id', $transactionData['transaction']['source_id'])
                ->first()) {
                return StoreTransaction::run(
                    order:     $order,
                    modelData: $transactionData['transaction']
                );
            }
        }


        return null;
    }


}