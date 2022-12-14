<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 21 Dec 2022 15:01:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Sales\InvoiceStats
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $number_items current number of items
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sales\Invoice $invoice
 * @method static Builder|InvoiceStats newModelQuery()
 * @method static Builder|InvoiceStats newQuery()
 * @method static Builder|InvoiceStats query()
 * @method static Builder|InvoiceStats whereCreatedAt($value)
 * @method static Builder|InvoiceStats whereId($value)
 * @method static Builder|InvoiceStats whereInvoiceId($value)
 * @method static Builder|InvoiceStats whereNumberItems($value)
 * @method static Builder|InvoiceStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InvoiceStats extends Model
{
    protected $table = 'invoice_stats';
    protected $guarded = [];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
