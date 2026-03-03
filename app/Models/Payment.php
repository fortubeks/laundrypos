<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function getPayments($order_id)
    {
        $order_id = Order::find($order_id);
        $payments = Payment::where('order_id', "{$order_id->id}")->paginate(10);
        return $payments;
    }
}
