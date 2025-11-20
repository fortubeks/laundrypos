<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

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

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function laundry_item()
    {
        return $this->belongsTo(LaundryItem::class, 'laundry_item_id');
    }
}
