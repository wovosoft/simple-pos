<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CustomerPayment
 *
 * @property int $id
 * @property int $customer_id
 * @property float $previous_balance
 * @property float $amount
 * @property float $current_balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CustomerPaymentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment whereCurrentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment wherePreviousBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Customer|null $customer
 */
class CustomerPayment extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
