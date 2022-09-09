<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 07 Sept 2022 03:36:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

use App\Actions\HumanResources\Employee\IndexEmployee;
use App\Actions\HumanResources\Employee\ShowEmployee;


Route::get('/employees', IndexEmployee::class)->name('employees.index');
Route::get('/employees/{employee}', ShowEmployee::class)->name('employees.show');