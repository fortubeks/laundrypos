<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function serviceItems()
    {
        return $this->belongsToMany(ServiceItem::class, 'order_service_item')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }
}
