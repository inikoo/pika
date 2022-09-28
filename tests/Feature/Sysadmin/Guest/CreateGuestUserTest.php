<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 26 Sept 2022 21:07:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace Tests\Feature\Sysadmin\Guest;

use App\Models\Central\Tenant;
use Illuminate\Validation\ValidationException;
use stdClass;
use Symfony\Component\Process\Process as Process;
use Tests\TestCase;

class CreateGuestUserTest extends TestCase
{


    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $process = new Process(['devops/load_test_snapshot.sh', 'devops/devel/snapshots/migrate-aurora-tenants.dump']);
        $process->run();
    }

    public function testGuestUserCommands()
    {
        $adminUserAData           = new stdClass();
        $adminUserAData->name     = fake()->name();
        $adminUserAData->email    = fake()->unique()->safeEmail();
        $adminUserAData->username = fake()->userName();

        $tenant=Tenant::where('code',env('TENANT'))->firstOrFail();

        $this->assertEquals(0, $tenant->stats->number_guests);
        $this->assertEquals(0, $tenant->stats->number_users);

        $this->artisan("create:guest-user  $adminUserAData->username '$adminUserAData->name'  -a -r super-admin")->assertExitCode(0);

        $tenant->refresh();

        $this->assertEquals(1, $tenant->stats->number_users);
        $this->assertEquals(1, $tenant->stats->number_guests);


        $adminUserAData->name     = fake()->name();
        $adminUserAData->email    = fake()->unique()->safeEmail();
        $adminUserAData->username = fake()->userName();

        $this->artisan("create:guest-user  $adminUserAData->username '$adminUserAData->name' ".env('TENANT')."  -a -r super-admin")->assertExitCode(0);
        $tenant->refresh();
        $this->assertEquals(2, $tenant->stats->number_guests);
        $this->assertEquals(2, $tenant->stats->number_users);

        $this->expectException(ValidationException::class);
        $this->artisan("create:guest-user  $adminUserAData->username '$adminUserAData->name' env('TENANT') -a -r super-admin")->assertExitCode(1);



    }



}
