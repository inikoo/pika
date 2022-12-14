<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 30 Aug 2022 19:10:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('location_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id');
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->unsignedMediumInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->decimal('quantity', 16, 3);
            $table->enum('type',['picking','storing']);
            $table->smallInteger('picking_priority')->nullable()->index();
            $table->string('notes')->nullable();
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->dateTimeTz('audited_at')->nullable()->index();
            $table->timestampsTz();
            $table->unsignedBigInteger('source_stock_id')->nullable();
            $table->unsignedBigInteger('source_location_id')->nullable();
            $table->unique(['stock_id', 'location_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('location_stock');
    }
};
