<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SaleItem
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_item_id
 * @property int $sale_id
 * @property int $quantity
 * @property int $delivered_quantity
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereDeliveredQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\ProductItem|null $productItem
 * @property int $cost
 * @method static \Illuminate\Database\Eloquent\Builder|SaleItem whereCost($value)
 */

class SaleItem extends Model
{
    use HasFactory;

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function addToSale()
    {
        return $this->productItem->addToSale($this->delivered_quantity);
    }
}
