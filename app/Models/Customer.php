<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'laundry_id',
        'title',
        'first_name',
        'last_name',
        'other_names',
        'email',
        'phone_code',
        'phone',
        'other_phone',
        'birthday',
        'address',
        'state_id',
        'country_id',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function laundry(): BelongsTo
    {
        return $this->belongsTo(Laundry::class);
    }
}
