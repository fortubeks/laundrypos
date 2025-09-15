<?php

namespace App\Observers;

use App\Models\Laundry;
use App\Models\LaundryItem;
use App\Models\ServiceCategory;
use App\Models\ServiceItem;

class LaundryObserver
{
    /**
     * Handle the Laundry "created" event.
     */
    public function created(Laundry $laundry): void
    {
        $defaultCategories = ['Washing', 'Ironing', 'Dry Cleaning', 'Bulk / Kg Wash'];
        $defaultLaundryItems = ['Shirt', 'Trousers', 'Suit', 'Bedsheet', 'Dress'];

        // Seed categories
        $categories = [];
        foreach ($defaultCategories as $category) {
            $categories[$category] = ServiceCategory::firstOrCreate([
                'laundry_id' => $laundry->id,
                'name' => $category,
            ]);
        }

        // Seed LaundryItems (for per-item services)
        foreach ($defaultLaundryItems as $item) {
            LaundryItem::firstOrCreate([
                'laundry_id' => $laundry->id,
                'name' => $item,
            ]);
        }

        // Seed a default per-kg service item (no laundry item needed)
        ServiceItem::firstOrCreate([
            'laundry_id'           => $laundry->id,
            'service_category_id'  => $categories['Bulk / Kg Wash']->id,
            'laundry_item_id'      => null,
            'name'                 => 'General Laundry (Per Kg)',
            'price'                => 500, // default price per kg, adjust as needed
            'unit_type'            => 'per_kg',
        ]);
    }

    /**
     * Handle the Laundry "updated" event.
     */
    public function updated(Laundry $laundry): void
    {
        //
    }

    /**
     * Handle the Laundry "deleted" event.
     */
    public function deleted(Laundry $laundry): void
    {
        //
    }

    /**
     * Handle the Laundry "restored" event.
     */
    public function restored(Laundry $laundry): void
    {
        //
    }

    /**
     * Handle the Laundry "force deleted" event.
     */
    public function forceDeleted(Laundry $laundry): void
    {
        //
    }
}
