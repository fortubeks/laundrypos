<?php
namespace Database\Seeders;

use App\Models\LaundryItem;
use App\Models\ServiceItem;
use Illuminate\Database\Seeder;

class DefaultLaundrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $laundryId
     * @return void
     */
    public function run($laundryId = null)
    {
        if (! $laundryId) {
            $this->command->error('No laundry ID provided, skipping default seeding.');
            return;
        }

        // Default laundry items
        $laundryItems = [
            'Shirt',
            'T-Shirt',
            'Trousers',
            'Jeans',
            'Jacket',
            'Bed Sheet',
            'Towel',
            'Scarf',
            'Suit',
            'Waistcoat',
            'Sweater',
            'Jumpsuit',
        ];

        $createdLaundryItems = [];

        foreach ($laundryItems as $itemName) {
            $laundryItem = LaundryItem::create([
                'laundry_id' => $laundryId,
                'name'       => $itemName,
            ]);
            $createdLaundryItems[$itemName] = $laundryItem->id;
        }

        // Default service items (reasonable prices)
        $serviceItems = [
            ['category_id' => 1, 'laundry_item' => 'Shirt', 'name' => 'Wash & Fold', 'price' => 500, 'unit_type' => 'per_item', 'turnaround_time' => 24],
            ['category_id' => 1, 'laundry_item' => 'T-Shirt', 'name' => 'Wash & Fold', 'price' => 400, 'unit_type' => 'per_item', 'turnaround_time' => 24],
            ['category_id' => 2, 'laundry_item' => 'Trousers', 'name' => 'Dry Clean', 'price' => 1200, 'unit_type' => 'per_item', 'turnaround_time' => 48],
            ['category_id' => 2, 'laundry_item' => 'Jacket', 'name' => 'Dry Clean', 'price' => 2500, 'unit_type' => 'per_item', 'turnaround_time' => 72],
            ['category_id' => 3, 'laundry_item' => 'Bed Sheet', 'name' => 'Wash & Iron', 'price' => 1500, 'unit_type' => 'per_item', 'turnaround_time' => 24],
        ];

        foreach ($serviceItems as $service) {
            ServiceItem::create([
                'laundry_id'          => $laundryId,
                'service_category_id' => $service['category_id'],
                'laundry_item_id'     => $createdLaundryItems[$service['laundry_item']] ?? null,
                'name'                => $service['name'],
                'price'               => $service['price'],
                'unit_type'           => $service['unit_type'],
                'turnaround_time'     => $service['turnaround_time'],
            ]);
        }

        $this->command->info('Default laundry items and services seeded.');
    }
}
