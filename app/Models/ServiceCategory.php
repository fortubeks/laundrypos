<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    // protected $fillable = ['laundry_id', 'name'];
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
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
