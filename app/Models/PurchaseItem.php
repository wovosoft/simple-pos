<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PurchaseItem
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_item_id
 * @property int $purchase_id
 * @property int $quantity
 * @property int $received_quantity
 * @property int $cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem whereReceivedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Purchase|null $purchase
 * @property-read \App\Models\ProductItem|null $productItem
 */
class PurchaseItem extends Model
{
    use HasFactory;

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function createProductItem()
    {
        $pi = new ProductItem();
        $pi->product_id = $this->product_id;
        $pi->cost = $this->cost;
        //received quantity should be stored as stock quantity
        $pi->quantity = $this->received_quantity;
        $pi->sold_quantity = 0; //not item is sold while creation
        $pi->saveOrFail();

        //add the product item id to the purchase item
        $this->product_item_id = $pi->id;
        $this->saveQuietly();

        return $pi;
    }

    /**
     * Initally when purchased there will have no product item association
     * but when items is returned from customer, or some other manner if
     * items needs to be added to stock, product item should be existed.
     * @return void
     */
    public function addToStock()
    {
        if (!$this->productItem && $this->received_quantity) {
            return $this->createProductItem()->addToStock($this->received_quantity);
        }

        return $this->productItem->addToStock($this->received_quantity);
    }
}
