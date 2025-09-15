<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $fillable = [
        'laundry_id',
        'service_category_id',
        'laundry_item_id', // nullable if per kg
        'name',
        'price',
        'unit_type', // per_item | per_kg
    ];
    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_service_item')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }
}
