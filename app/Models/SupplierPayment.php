<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SupplierPayment
 *
 * @property int $id
 * @property int $supplier_id
 * @property float $previous_balance
 * @property float $amount
 * @property float $current_balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Database\Factories\SupplierPaymentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment whereCurrentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment wherePreviousBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $purpose
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment wherePurpose($value)
 */
class SupplierPayment extends Model
{
    use HasFactory;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
