<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

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
        if (! $this->phone) {
            return null;
        }

        // Remove spaces
        $whatsapp_number = removeSpaces($this->phone);

        // Remove +
        $whatsapp_number = ltrim($whatsapp_number, '+');

        // Remove leading 0
        if (str_starts_with($whatsapp_number, '0')) {
            $whatsapp_number = substr($whatsapp_number, 1);
        }

        $countryCode = null;

        if ($this->country) {
            $countryCode = $this->country->phonecode;
        } elseif ($user && $user->user_account->app_settings->business_currency) {
            $country = Country::where('currency', $user->user_account->app_settings->business_currency)->first();
            if ($country) {
                $countryCode = $country->phonecode;
            }
        }

        if ($countryCode) {

            // If number already starts with the country code, don't add it again
            if (str_starts_with($whatsapp_number, $countryCode)) {
                return $whatsapp_number;
            }

            return $countryCode . $whatsapp_number;
        }

        return $whatsapp_number;
    }
}
