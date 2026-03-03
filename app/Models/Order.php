<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
    public function items()
    {
        return $this->hasMany(OrderServiceItem::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getOutstandingBalance()
    {
        return $this->total_amount - $this->payments->sum('amount');
    }
    public function getAmountPaid()
    {
        //check all payments that have the order_id
        $payments = Payment::where('order_id', $this->id)->sum('amount');
        return $payments;
    }

}
