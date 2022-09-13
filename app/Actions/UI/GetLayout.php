<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 07 Sept 2022 22:03:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\UI;


use App\Models\SysAdmin\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLayout
{
    use AsAction;

    public function handle(User $user): array
    {
        $navigation = [
            [
                'name'  => __('dashboard'),
                'icon'  => ['fal', 'fa-tachometer-alt-fast'],
                'route' => 'dashboard'
            ]
        ];


        if ($user->can('warehouses.dispatching.pick')) {
            $navigation[] = [
                'name'  => __('picking'),
                'icon'  => ['fal', 'fa-dolly-flatbed-alt'],
                'route' => 'dashboard'
            ];
        }

        if ($user->can('warehouses.dispatching.pack')) {
            $navigation[] = [
                'name'  => __('packing'),
                'icon'  => ['fal', 'fa-conveyor-belt-alt'],
                'route' => 'dashboard'
            ];
        }

        if ($user->can('hr.view')) {
            $navigation[] = [
                'name'  => __('human resources'),
                'icon'  => ['fal', 'fa-user-hard-hat'],
                'route' => 'hr.dashboard'
            ];
        }

        if ($user->can('sysadmin.view')) {
            $navigation[] = [
                'name'  => __('Sysadmin'),
                'icon'  => ['fal', 'fa-users-cog'],
                'route' => 'sysadmin.dashboard'
            ];
        }


        return [
            'navigation' => $navigation
        ];
    }

}
