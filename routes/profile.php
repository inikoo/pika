<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 09 Sept 2022 18:32:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


use App\Actions\SysAdmin\Profile\ShowProfile;
use App\Actions\SysAdmin\Profile\UpdateProfile;

Route::get('/', ShowProfile::class)->name('show');
Route::patch('/', UpdateProfile::class)->name('update');