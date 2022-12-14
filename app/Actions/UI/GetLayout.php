<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 07 Sept 2022 22:03:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\UI;


use App\Models\Central\Tenant;
use App\Models\Marketing\Shop;
use App\Models\SysAdmin\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLayout
{
    use AsAction;

    public function handle(User $user): array
    {
        /** @var Tenant $tenant */
        $tenant    = tenant();
        $shopCount = $tenant->marketingStats->number_shops;
        $shop      = null;
        if ($shopCount == 1) {
            $shop = Shop::first();
        }

        $navigation = [
            [
                'name'  => __('dashboard'),
                'icon'  => ['fal', 'fa-tachometer-alt-fast'],
                'route' => 'dashboard'
            ]
        ];

        if ($shopCount == 1) {
            if ($user->can('shops.view')) {
                $navigation[] = [
                    'name'            => __('shop'),
                    'icon'            => ['fal', 'fa-store-alt'],
                    'route'           => 'shops.show',
                    'routeParameters' => $shop->id
                ];
            }
            if ($user->can('websites.view')) {
                $navigation[] = [
                    'name'  => __('websites'),
                    'icon'  => ['fal', 'fa-globe'],
                    'route' => 'websites.index'
                ];
            }
            if ($user->can('shops.customers.view')) {
                $navigation[] = [
                    'name'            => __('customers'),
                    'icon'            => ['fal', 'fa-user'],
                    'route'           => 'shops.show.customers.index',
                    'routeParameters' => $shop->id

                ];
            }
        } else {
            if ($user->can('shops.view')) {
                $navigation[] = [
                    'name'  => __('shops'),
                    'icon'  => ['fal', 'fa-store-alt'],
                    'route' => 'shops.index'
                ];
            }
            if ($user->can('websites.view')) {
                $navigation[] = [
                    'name'  => __('websites'),
                    'icon'  => ['fal', 'fa-globe'],
                    'route' => 'websites.index'
                ];
            }
            if ($user->can('shops.customers.view')) {
                $navigation[] = [
                    'name'  => __('customers'),
                    'icon'  => ['fal', 'fa-user'],
                    'route' => 'customers.index'
                ];
            }
        }


        if ($user->can('inventory.view')) {
            $navigation[] = [
                'name'  => __('inventory'),
                'icon'  => ['fal', 'fa-inventory'],
                'route' => 'inventory.dashboard'
            ];
        }

        if ($user->can('fulfilment.view')) {
            $navigation[] = [
                'name'  => __('fulfilment'),
                'icon'  => ['fal', 'fa-dolly-empty'],
                'route' => 'fulfilment.dashboard'
            ];
        }

        if ($user->can('production.view')) {
            $navigation[] = [
                'name'  => __('production'),
                'icon'  => ['fal', 'fa-industry'],
                'route' => 'production.dashboard'
            ];
        }

        if ($user->can('procurement.view')) {
            $navigation[] = [
                'name'  => __('procurement'),
                'icon'  => ['fal', 'fa-parachute-box'],
                'route' => 'procurement.dashboard'
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

        $actions = [];

        if ($user->can('dispatching.pick')) {
            $actions[] = [
                'name'  => __('picking'),
                'icon'  => ['fal', 'fa-dolly-flatbed-alt'],
                'route' => 'dashboard',
                'color' => 'bg-indigo-500'
            ];
        }

        if ($user->can('dispatching.pack')) {
            $actions[] = [
                'name'  => __('packing'),
                'icon'  => ['fal', 'fa-conveyor-belt-alt'],
                'route' => 'dashboard',
                'color' => 'bg-green-500'
            ];
        }


        return [
            'navigation' => $navigation,
            'actions'    => $actions
        ];
    }

}
