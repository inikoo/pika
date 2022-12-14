<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Aug 2022 14:07:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            //these are no normal, hydra-table from contact
            $table->string('name', 256)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('identity_document_type')->nullable();
            $table->string('identity_document_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Make', 'Female', 'Other'])->nullable();
            //=====
            $table->string('worker_number')->nullable();
            $table->string('job_title')->nullable();

            $table->enum('type', ['employee', 'volunteer', 'temporal-worker', 'work-experience'])->default('employee');
            $table->enum('state', ['hired', 'working', 'left'])->default('working');
            $table->date('employment_start_at')->nullable();
            $table->date('employment_end_at')->nullable();
            $table->string('emergency_contact', 1024)->nullable();
            $table->jsonb('salary')->nullable();
            $table->jsonb('working_hours')->nullable();
            $table->decimal('week_working_hours',4,2)->default(0);

            $table->jsonb('data');
            $table->jsonb('job_position_scopes');

            $table->jsonb('errors');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('source_id')->nullable()->unique();

        });


    }


    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
