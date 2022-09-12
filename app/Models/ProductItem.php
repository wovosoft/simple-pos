<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductItem
 *
 * @property int $id
 * @property int $product_id
 * @property float $cost
 * @property float $quantity
 * @property float $sold_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ProductItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereSoldQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float $damaged_quantity
 * @property float $returned_quantity
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereDamagedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereReturnedQuantity($value)
 */
class ProductItem extends Model
{
    use HasFactory;

    public function addToStock(int|float $quantity)
    {
        return $this->increment("quantity", $quantity);
    }

    /**
     * Updating sold quantity 
     * The main quantity field is not changed. 
     *
     * @param integer|float $quantity
     * @return void
     */
    public function addToSale(int|float $quantity)
    {
        return $this->increment("sold_quantity", $quantity);
    }
}
