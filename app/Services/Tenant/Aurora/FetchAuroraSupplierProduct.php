<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 25 Oct 2022 21:38:36 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Services\Tenant\Aurora;

use App\Actions\SourceFetch\Aurora\FetchSuppliers;
use Illuminate\Support\Facades\DB;

class FetchAuroraSupplierProduct extends FetchAurora
{
    protected function parseModel(): void
    {
        $this->parsedData['owner'] = FetchSuppliers::run($this->tenantSource, $this->auroraModelData->{'Supplier Part Supplier Key'});

        $sharedData = [];
        $settings   = [];

        $status = 1;
        if ($this->auroraModelData->{'Supplier Part Status'} == 'Discontinued') {
            $status = 0;
        }
        $state = match ($this->auroraModelData->{'Supplier Part Status'}) {
            'NoAvailable' => 'no-available',
            'Discontinued' => 'discontinued',
            default => 'active',
        };


        if ($this->auroraModelData->{'Supplier Part From'} == '0000-00-00 00:00:00') {
            $created_at = null;
        } else {
            $created_at = $this->auroraModelData->{'Supplier Part From'};
        }

        $sharedData['raw_price'] = $this->auroraModelData->{'Supplier Part Unit Cost'} ?? 0;


        $stock_quantity_status = match ($this->auroraModelData->{'Part Stock Status'}) {
            'Out_Of_Stock', 'Error' => 'out-of-stock',
            default => strtolower($this->auroraModelData->{'Part Stock Status'})
        };


        $this->parsedData['supplierProduct'] =
            [
                'code' => $this->auroraModelData->{'Supplier Part Reference'},
                'name' => $this->auroraModelData->{'Supplier Part Description'},

                'cost'             => round($this->auroraModelData->{'Supplier Part Unit Cost'} ?? 0, 2),
                'units_per_pack'   => $this->auroraModelData->{'Part Units Per Package'},
                'units_per_carton' => $this->auroraModelData->{'Supplier Part Packages Per Carton'} * $this->auroraModelData->{'Part Units Per Package'},


                'status'                => $status,
                'state'                 => $state,
                'stock_quantity_status' => $stock_quantity_status,

                'shared_data' => $sharedData,
                'settings'    => $settings,
                'created_at'  => $created_at,
                'source_id'   => $this->auroraModelData->{'Supplier Part Key'}
            ];
    }


    protected function fetchData($id): object|null
    {
        return DB::connection('aurora')
            ->table('Supplier Part Dimension')
            ->leftjoin('Part Dimension', 'Supplier Part Part SKU', 'Part SKU')
            ->where('Supplier Part Key', $id)->first();
    }

}
