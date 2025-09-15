<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderServiceItem extends Model
{
    protected $fillable = [
        'order_id',
        'service_item_id',
        'quantity', // used for per_item
        'weight',   // used for per_kg
        'price',    // locked-in unit price at order time
    ];
}
