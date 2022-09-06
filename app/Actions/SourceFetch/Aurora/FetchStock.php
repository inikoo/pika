<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 05 Sept 2022 00:35:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\SourceFetch\Aurora;


use App\Actions\Inventory\Stock\StoreStock;
use App\Actions\Inventory\Stock\UpdateStock;
use App\Actions\SourceUpserts\Aurora\Single\UpsertTradeUnitFromSource;
use App\Models\Inventory\Stock;
use App\Services\Organisation\SourceOrganisationService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;


class FetchStock extends Fetch
{

    public string $commandSignature = 'fetch:stocks {organisation_code} {organisation_source_id?}';

    #[NoReturn] public function handle(SourceOrganisationService $organisationSource, int $organisationSourceId): ?Stock
    {
        if ($stockData = $organisationSource->fetchStock($organisationSourceId)) {
            if ($stock = Stock::where('organisation_source_id', $stockData['stock']['organisation_source_id'])
                ->where('organisation_id', $organisationSource->organisation->id)
                ->first()) {
                $res = UpdateStock::run(
                    stock:     $stock,
                    modelData: $stockData['stock'],
                );
            } else {
                $res = StoreStock::run(
                    owner:     $organisationSource->organisation,
                    modelData: $stockData['stock']
                );
            }
            $stock     = $res->model;
            $tradeUnit = UpsertTradeUnitFromSource::run($organisationSource, $res->model->organisation_source_id);
            $stock->tradeUnits()->sync([
                                           $tradeUnit->id => [
                                               'quantity' => $stockData['units_per_package']
                                           ]
                                       ]);

            $locationsData = $organisationSource->fetchStockLocations($organisationSourceId);

            $stock->locations()->sync($locationsData['stock_locations']);

            $this->progressBar?->advance();

            return $stock;
        }


        return null;
    }

    function getModelsQuery(): Builder
    {
        return DB::connection('aurora')
            ->table('Part Dimension')
            ->select('Part SKU as source_id')
            ->where('Part Status', '!=', 'Not In Use')
            ->orderBy('source_id');
    }

    function count(): ?int
    {
        return DB::connection('aurora')->table('Part Dimension')->where('Part Status', '!=', 'Not In Use')->count();
    }

}
