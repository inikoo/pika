<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 22 Sept 2022 02:23:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


/** @noinspection PhpUnused */

namespace App\Actions\SourceFetch\Aurora;

use App\Actions\Marketing\HistoricProduct\StoreHistoricProduct;
use App\Actions\Marketing\HistoricProduct\UpdateHistoricProduct;
use App\Models\Marketing\HistoricProduct;
use App\Services\Tenant\SourceTenantService;
use JetBrains\PhpStorm\NoReturn;
use Lorisleiva\Actions\Concerns\AsAction;


class FetchHistoricProducts
{
    use AsAction;


    #[NoReturn] public function handle(SourceTenantService $tenantSource, int $source_id): ?HistoricProduct
    {
        if ($historicProductData = $tenantSource->fetchHistoricProduct($source_id)) {


            if ($historicProduct = HistoricProduct::withTrashed()->where('source_id', $historicProductData['historic_product']['source_id'])
                ->first()) {
                $historicProduct = UpdateHistoricProduct::run(
                    historicProduct: $historicProduct,
                    modelData:       $historicProductData['historic_product'],
                );
            } else {
                $historicProduct = StoreHistoricProduct::run(
                    product:   $historicProductData['product'],
                    modelData: $historicProductData['historic_product']
                );
            }


            return $historicProduct;
        }


        return null;
    }


}
