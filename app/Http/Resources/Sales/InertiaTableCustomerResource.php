<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Oct 2022 01:22:24 Greenwich Mean Time, Plane HK->KL
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $reference
 * @property string $shop_code
 * @property string $shop_slug
 * @property int $number_active_clients
 * @property string $slug
 */
class InertiaTableCustomerResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'                    => $this->id,
            'slug'                  => $this->slug,
            'name'                  => $this->name,
            'reference'             => $this->reference,
            'shop'                  => $this->shop_code,
            'shop_slug'             => $this->shop_slug,
            'number_active_clients' => $this->number_active_clients,
        ];
    }
}
