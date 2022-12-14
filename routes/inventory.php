<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 14 Sept 2022 15:11:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


use App\Actions\Inventory\Location\IndexLocations;
use App\Actions\Inventory\Location\ShowLocation;
use App\Actions\Inventory\ShowInventoryDashboard;
use App\Actions\Inventory\Stock\IndexStocks;
use App\Actions\Inventory\Stock\ShowStock;
use App\Actions\Inventory\StockFamily\IndexStockFamilies;
use App\Actions\Inventory\StockFamily\ShowStockFamily;
use App\Actions\Inventory\Warehouse\IndexWarehouses;
use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Actions\Inventory\WarehouseArea\IndexWarehouseAreas;
use App\Actions\Inventory\WarehouseArea\ShowWarehouseArea;



Route::get('/', ShowInventoryDashboard::class)->name('dashboard');

Route::get('/warehouses', IndexWarehouses::class)->name('warehouses.index');
Route::get('/warehouses/{warehouse}', ShowWarehouse::class)->name('warehouses.show');


Route::get('/areas', [IndexWarehouseAreas::class, 'inOrganisation'])->name('warehouse_areas.index');
Route::get('/areas/{warehouseArea}', [ShowWarehouseArea::class, 'inOrganisation'])->name('warehouse_areas.show');


Route::get('/locations', [IndexLocations::class, 'inOrganisation'])->name('locations.index');
Route::get('/locations/{location}', [ShowLocation::class, 'inOrganisation'])->name('locations.show');

Route::scopeBindings()->group(function () {
    Route::get('/areas/{warehouseArea}/locations', [IndexLocations::class, 'inWarehouseArea'])->name('warehouse_areas.show.locations.index');
    Route::get('/areas/{warehouseArea}/locations/{location}', [ShowLocation::class, 'inWarehouseArea'])->name('warehouse_areas.show.locations.show');


    Route::get('/warehouses/{warehouse}/areas', [IndexWarehouseAreas::class, 'inWarehouse'])->name('warehouses.show.warehouse_areas.index');
    Route::get('/warehouses/{warehouse}/areas/{warehouseArea}', [ShowWarehouseArea::class, 'inWarehouse'])->name('warehouses.show.warehouse_areas.show');

    Route::get('/warehouses/{warehouse}/areas/{warehouseArea}/locations', [IndexLocations::class, 'InWarehouseInWarehouseArea'])->name('warehouses.show.warehouse_areas.show.locations.index');
    Route::get('/warehouses/{warehouse}/areas/{warehouseArea}/locations/{location}', [ShowLocation::class, 'InWarehouseInWarehouseArea'])->name('warehouses.show.warehouse_areas.show.locations.show');


    Route::get('/warehouses/{warehouse}/locations', [IndexLocations::class, 'inWarehouse'])->name('warehouses.show.locations.index');
    Route::get('/warehouses/{warehouse}/locations/{location}', [ShowLocation::class, 'inWarehouse'])->name('warehouses.show.locations.show');
});

Route::get('/families', IndexStockFamilies::class)->name('stock-families.index');
Route::get('/families/{stockFamily:slug}', ShowStockFamily::class)->name('stock-families.show');

Route::get('/stocks', IndexStocks::class)->name('stocks.index');
Route::get('/stocks/{stock}', ShowStock::class)->name('stocks.show');
