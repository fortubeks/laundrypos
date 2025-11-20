<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryItem extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    // protected $fillable = ['laundry_id', 'name'];
    protected $guarded = ['id'];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class);
    }
}
