<?php /*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 30 Aug 2022 13:33:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


namespace App\Actions\SourceUpserts\Aurora\Mass;

use App\Actions\SourceUpserts\Aurora\Single\UpsertLocationFromSource;
use App\Services\Organisation\SourceOrganisationService;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;
use Lorisleiva\Actions\Concerns\AsAction;


class UpsertLocationsFromSource
{
    use AsAction;
    use WithMassFromSourceCommand;

    public string $commandSignature = 'source-update:locations {organisation_code}';


    #[NoReturn] public function handle(SourceOrganisationService $organisationSource): void
    {
        foreach (
            DB::connection('aurora')
                ->table('Location Dimension')
                ->select('Location Key')
                ->get() as $auroraData
        ) {

            UpsertLocationFromSource::run($organisationSource, $auroraData->{'Location Key'});
        }
    }


}