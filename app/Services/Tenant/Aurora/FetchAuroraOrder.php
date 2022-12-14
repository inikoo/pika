<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 29 Aug 2022 17:12:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Services\Tenant\Aurora;

use App\Actions\SourceFetch\Aurora\FetchCustomerClients;
use App\Models\Helpers\Address;
use Illuminate\Support\Facades\DB;

class FetchAuroraOrder extends FetchAurora
{
    protected function parseModel(): void
    {
        if ($this->auroraModelData->{'Order State'} == "InBasket") {
            return;
        }

        if ($this->auroraModelData->{'Order Customer Client Key'} != "") {
            $parent = FetchCustomerClients::run(
                $this->tenantSource,
                $this->auroraModelData->{'Order Customer Client Key'},
            );
        } else {
            $parent = $this->parseCustomer(
                $this->auroraModelData->{'Order Customer Key'}
            );
        }
        $this->parsedData["parent"] = $parent;

        $state = match ($this->auroraModelData->{'Order State'}) {
            "InWarehouse", "Packed" => "in-warehouse",
            "PackedDone" => "packed",
            "Approved" => "finalised",
            "Dispatched" => "dispatched",
            default => "submitted",
        };

        $cancelled_at = null;
        if ($this->auroraModelData->{'Order State'} == "Cancelled") {
            $cancelled_at = $this->auroraModelData->{'Order Cancelled Date'};
            if (!$cancelled_at) {
                $cancelled_at = $this->auroraModelData->{'Order Date'};
            }

            if (
                $this->auroraModelData->{'Order Invoiced Date'} != "" or
                $this->auroraModelData->{'Order Dispatched Date'} != ""
            ) {
                $state = "finalised";
            } elseif (
                $this->auroraModelData->{'Order Packed Date'} != "" or
                $this->auroraModelData->{'Order Packed Done Date'} != ""
            ) {
                $state = "packed";
            } elseif (
                $this->auroraModelData->{'Order Send to Warehouse Date'} != ""
            ) {
                $state = "in-warehouse";
            } else {
                $state = "submitted";
            }
        }

        $this->parsedData["order"] = [
            "number"          => $this->auroraModelData->{'Order Public ID'},
            'customer_number' => $this->auroraModelData->{'Order Customer Purchase Order ID'},
            "state"           => $state,
            "source_id"       => $this->auroraModelData->{'Order Key'},
            "exchange"        => $this->auroraModelData->{'Order Currency Exchange'},
            "created_at"      => $this->auroraModelData->{'Order Created Date'},
            "cancelled_at"    => $cancelled_at,
        ];

        $deliveryAddressData                  = $this->parseAddress(
            prefix:        "Order Delivery",
            auAddressData: $this->auroraModelData,
        );
        $this->parsedData["delivery_address"] = new Address(
            $deliveryAddressData,
        );

        $billingAddressData                  = $this->parseAddress(
            prefix:        "Order Invoice",
            auAddressData: $this->auroraModelData,
        );
        $this->parsedData["billing_address"] = new Address($billingAddressData);
    }

    protected function fetchData($id): object|null
    {
        return DB::connection("aurora")
            ->table("Order Dimension")
            ->where("Order Key", $id)
            ->first();
    }
}
