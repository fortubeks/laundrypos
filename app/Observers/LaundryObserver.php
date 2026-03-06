<?php
namespace App\Observers;

use App\Models\Laundry;
use App\Models\LaundryItem;
use App\Models\ServiceCategory;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;

class LaundryObserver
{
    /**
     * Handle the Laundry "created" event.
     */

    public function created(Laundry $laundry): void
    {
        DB::transaction(function () use ($laundry) {

            // Categories
            $defaultCategories = ['Washing', 'Ironing', 'Dry Cleaning', 'Bulk / Kg Wash', 'Special Treatments'];
            $categories        = [];
            foreach ($defaultCategories as $category) {
                $categories[$category] = ServiceCategory::updateOrCreate(
                    ['laundry_id' => $laundry->id, 'name' => $category],
                    ['laundry_id' => $laundry->id, 'name' => $category]
                );
            }

            // Expanded laundry items
            $defaultLaundryItems = [
                'Shirt', 'T-Shirt', 'Blouse', 'Polo Shirt', 'Tank Top', 'Trousers', 'Jeans', 'Shorts', 'Skirt', 'Dress',
                'Suit', 'Jacket', 'Coat', 'Sweater', 'Cardigan', 'Hoodie', 'Vest', 'Tie', 'Socks', 'Underwear',
                'Bedsheet', 'Pillowcase', 'Blanket', 'Quilt', 'Towel', 'Curtain', 'Tablecloth', 'Napkin', 'Cushion Cover',
                'Bedspread', 'Bathrobe', 'Sportswear', 'Gym Shorts', 'Swimwear', 'Uniform', 'Baby Clothes', 'Baby Blanket',
                'Scarf', 'Gloves', 'Hat', 'Shoes', 'Sneakers', 'Leather Jacket', 'Denim Jacket', 'Silk Shirt', 'Wool Sweater',
                'Comforter', 'Duvet', 'Rug', 'Carpet', 'Apron', 'Kitchen Towel', 'Handkerchief', 'Face Mask',
            ];

            $laundryItemMap = [];
            foreach ($defaultLaundryItems as $item) {
                $laundryItem = LaundryItem::updateOrCreate(
                    ['laundry_id' => $laundry->id, 'name' => $item],
                    ['laundry_id' => $laundry->id, 'name' => $item]
                );
                $laundryItemMap[$item] = $laundryItem->id;
            }

            // Expanded per-item services
            // Expanded per-item services
            $defaultServices = [
                // Washing
                ['category' => 'Washing', 'item' => 'Shirt', 'action' => 'Wash & Fold', 'price' => 500, 'unit_type' => 'per_item', 'turnaround_time' => 24],
                ['category' => 'Washing', 'item' => 'T-Shirt', 'action' => 'Wash & Fold', 'price' => 400, 'unit_type' => 'per_item', 'turnaround_time' => 24],
                ['category' => 'Washing', 'item' => 'Polo Shirt', 'action' => 'Wash & Fold', 'price' => 450, 'unit_type' => 'per_item', 'turnaround_time' => 24],
                ['category' => 'Washing', 'item' => 'Trousers', 'action' => 'Wash & Iron', 'price' => 600, 'unit_type' => 'per_item', 'turnaround_time' => 24],
                ['category' => 'Washing', 'item' => 'Jeans', 'action' => 'Wash & Iron', 'price' => 700, 'unit_type' => 'per_item', 'turnaround_time' => 24],
                ['category' => 'Washing', 'item' => 'Bedsheet', 'action' => 'Wash & Iron', 'price' => 1500, 'unit_type' => 'per_item', 'turnaround_time' => 24],
                ['category' => 'Washing', 'item' => 'Blanket', 'action' => 'Wash & Iron', 'price' => 2000, 'unit_type' => 'per_item', 'turnaround_time' => 48],

                // Ironing
                ['category' => 'Ironing', 'item' => 'Shirt', 'action' => 'Iron Only', 'price' => 300, 'unit_type' => 'per_item', 'turnaround_time' => 12],
                ['category' => 'Ironing', 'item' => 'Trousers', 'action' => 'Iron Only', 'price' => 350, 'unit_type' => 'per_item', 'turnaround_time' => 12],

                // Dry Cleaning
                ['category' => 'Dry Cleaning', 'item' => 'Suit', 'action' => 'Dry Clean', 'price' => 2500, 'unit_type' => 'per_item', 'turnaround_time' => 72],
                ['category' => 'Dry Cleaning', 'item' => 'Jacket', 'action' => 'Dry Clean', 'price' => 2000, 'unit_type' => 'per_item', 'turnaround_time' => 72],
                ['category' => 'Dry Cleaning', 'item' => 'Dress', 'action' => 'Dry Clean', 'price' => 1800, 'unit_type' => 'per_item', 'turnaround_time' => 72],

                // Special Treatments
                ['category' => 'Special Treatments', 'item' => 'Tie', 'action' => 'Starch & Press', 'price' => 200, 'unit_type' => 'per_item', 'turnaround_time' => 12],
                ['category' => 'Special Treatments', 'item' => 'Silk & Wool', 'action' => 'Delicate Care', 'price' => 2000, 'unit_type' => 'per_item', 'turnaround_time' => 72],
            ];

            foreach ($defaultServices as $service) {
                $serviceName = $service['item'] . ' ' . $service['action']; // Combine item + action for service name

                ServiceItem::updateOrCreate(
                    [
                        'laundry_id'          => $laundry->id,
                        'service_category_id' => $categories[$service['category']]->id,
                        'laundry_item_id'     => $laundryItemMap[$service['item']] ?? null,
                        'name'                => $serviceName,
                    ],
                    [
                        'price'           => $service['price'],
                        'unit_type'       => $service['unit_type'],
                        'turnaround_time' => $service['turnaround_time'],
                    ]
                );
            }

            // Per-kg / bulk services
            $perKgServices = [
                ['category' => 'Bulk / Kg Wash', 'name' => 'General Laundry (Per Kg)', 'price' => 500, 'unit_type' => 'per_kg', 'turnaround_time' => 24],
                ['category' => 'Bulk / Kg Wash', 'name' => 'Dry Cleaning (Per Kg)', 'price' => 1200, 'unit_type' => 'per_kg', 'turnaround_time' => 48],
                ['category' => 'Bulk / Kg Wash', 'name' => 'Ironing (Per Kg)', 'price' => 400, 'unit_type' => 'per_kg', 'turnaround_time' => 24],
                ['category' => 'Special Treatments', 'name' => 'Delicate Fabrics', 'price' => 1500, 'unit_type' => 'per_kg', 'turnaround_time' => 48],
                ['category' => 'Special Treatments', 'name' => 'Silk & Wool Care', 'price' => 2000, 'unit_type' => 'per_kg', 'turnaround_time' => 72],
            ];

            foreach ($perKgServices as $service) {
                ServiceItem::updateOrCreate(
                    [
                        'laundry_id'          => $laundry->id,
                        'service_category_id' => $categories[$service['category']]->id,
                        'name'                => $service['name'],
                        'laundry_item_id'     => null,
                    ],
                    [
                        'price'           => $service['price'],
                        'unit_type'       => $service['unit_type'],
                        'turnaround_time' => $service['turnaround_time'],
                    ]
                );
            }

        });
    }

    public function updated(Laundry $laundry): void
    {}
    public function deleted(Laundry $laundry): void
    {}
    public function restored(Laundry $laundry): void
    {}
    public function forceDeleted(Laundry $laundry): void
    {}
}
