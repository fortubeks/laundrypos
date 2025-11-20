<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\LaundryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LaundryItemController extends Controller
{
    public function get_laundry_items(Request $request)
    {
        $user    = $request->user();
        $search  = $request->query('search');
        $perPage = $request->query('per_page', 20);

        $query = LaundryItem::where('laundry_id', $user->laundry_id);

        // Apply search only when provided
        if (! empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $items = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return ApiHelper::validResponse('Laundry items retrieved successfully!', $items);
    }

    public function get_laundry_item(Request $request, $id)
    {
        $user = $request->user();

        try {
            $laundryItem = LaundryItem::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            return ApiHelper::validResponse('Laundry item retrieved successfully!', $laundryItem);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Laundry item not found', 404);
        }
    }

    public function create_laundry_item(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $laundryItem = LaundryItem::create([
                'laundry_id' => $user->laundry_id,
                'name'       => $request->name,
            ]);

            return ApiHelper::validResponse('Laundry item created successfully!', $laundryItem);
        } catch (\Exception $e) {
            Log::error('Error creating laundry item: ' . $e->getMessage());
            return ApiHelper::problemResponse('Failed to create laundry item', 500);
        }
    }

    public function update_laundry_item(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $laundryItem = LaundryItem::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $laundryItem->update([
                'name' => $request->name,
            ]);

            return ApiHelper::validResponse('Laundry item updated successfully!', $laundryItem);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to update laundry item', 500);
        }
    }

    public function delete_laundry_item(Request $request, $id)
    {
        $user = $request->user();

        try {
            $laundryItem = LaundryItem::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $laundryItem->delete();
            return ApiHelper::validResponse('Laundry item deleted successfully!', null);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to delete laundry item', 500);
        }
    }
}
