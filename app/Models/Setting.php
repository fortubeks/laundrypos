<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * Check if business information is complete
     * @return bool
     */
    public function isBusinessInfoComplete()
    {
        $requiredFields = [
            'business_name',
            'business_phone',
            'business_address',
            'business_currency',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->{$field})) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get missing required fields
     * @return array
     */
    public function getMissingFields()
    {
        $requiredFields = [
            'business_name',
            'business_phone',
            'business_address',
            'business_currency',
        ];

        $missing = [];
        foreach ($requiredFields as $field) {
            if (empty($this->{$field})) {
                $missing[] = $field;
            }
        }

        return $missing;
    }
}
