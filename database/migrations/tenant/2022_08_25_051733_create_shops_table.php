<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Aug 2022 13:18:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('slug')->unique()->nullable();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('company_name', 256)->nullable();
            $table->string('contact_name', 256)->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('url', 256)->nullable();
            $table->string('tax_number')->nullable()->index();
            $table->enum('tax_number_status', ['valid', 'invalid', 'na', 'unknown'])->nullable()->default('na');
            $table->string('identity_document_type')->nullable();
            $table->string('identity_document_number')->nullable();
            $table->unsignedBigInteger('address_id')->nullable()->index();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->jsonb('location');

            $shopStates = ['in-process', 'open', 'closing-down', 'closed'];

            $table->enum('state', $shopStates)->index();
            $table->enum('type', ['shop', 'fulfilment_house', 'agent'])->index();
            $shopSubtypes = ['b2b', 'b2c', 'storage', 'fulfilment', 'dropshipping'];

            $table->enum('subtype', $shopSubtypes)->nullable();

            $table->date('open_at')->nullable();
            $table->date('closed_at')->nullable();
            $table->unsignedSmallInteger('language_id');
            $table->foreign('language_id')->references('id')->on('central.languages');
            $table->unsignedSmallInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('central.currencies');
            $table->unsignedSmallInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('central.timezones');
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('source_id')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shops');
    }
};
