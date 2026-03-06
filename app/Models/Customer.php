<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class Customer extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function whatsappNumber($user = null)
    {
        //if number has space remove it
        $whatsapp_number = removeSpaces($this->phone);
        //if number has 0 as first character remove it
        $whatsapp_number = ltrim($whatsapp_number, '0');
        //if number has + remove it
        if (substr($whatsapp_number, 0, 1) === '+') {
            return ltrim($whatsapp_number, '+');
        }
        if ($this->country) {
            return $this->country->phonecode . $whatsapp_number;
        } else {
            if ($user && $user->user_account->app_settings->business_currency) {
                $country = Country::where('currency', $user->user_account->app_settings->business_currency)->first();
                if ($country) {
                    return $country->phonecode . $whatsapp_number;
                } else {
                    return $whatsapp_number;
                }
            }
        }

    }
}
