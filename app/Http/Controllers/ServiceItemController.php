<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceItemController extends Controller
{
    public function get_service_items(Request $request)
    {
        $user    = $request->user();
        $search  = $request->query('search');
        $perPage = $request->query('per_page', 20);

        $query = ServiceItem::with(['category', 'laundry_item'])->where('laundry_id', $user->laundry_id);

        if (! empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $items = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return ApiHelper::validResponse('Service items retrieved successfully!', $items);
    }

    public function get_service_item(Request $request, $id)
    {
        $user = $request->user();

        $serviceItem = ServiceItem::where('id', $id)->where('laundry_id', $user->laundry_id)->firstOrFail();

        return ApiHelper::validResponse('Service item retrieved successfully!', $serviceItem);
    }

    public function create_service_item(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_category_id' => 'required|integer',
            'laundry_item_id'     => 'nullable|integer',
            'name'                => 'required|string|max:150',
            'price'               => 'required|numeric|min:0',
            'unit_type'           => 'required|in:per_item,per_kg',
            'turnaround_time'     => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $serviceItem = ServiceItem::create([
                'laundry_id'          => $user->laundry_id,
                'service_category_id' => $request->service_category_id,
                'laundry_item_id'     => $request->laundry_item_id,
                'name'                => $request->name,
                'price'               => $request->price,
                'unit_type'           => $request->unit_type,
                'turnaround_time'     => $request->turnaround_time,
            ]);

            return ApiHelper::validResponse('Service item created successfully!', $serviceItem);
        } catch (\Exception $e) {
            Log::error('Error creating service item: ' . $e->getMessage());
            return ApiHelper::problemResponse('Failed to create service item', 500);
        }
    }

    public function update_service_item(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'service_category_id' => 'required|integer',
            'laundry_item_id'     => 'nullable|integer',
            'name'                => 'required|string|max:150',
            'price'               => 'required|numeric|min:0',
            'unit_type'           => 'required|in:per_item,per_kg',
            'turnaround_time'     => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $serviceItem = ServiceItem::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $serviceItem->update([
                'service_category_id' => $request->service_category_id,
                'laundry_item_id'     => $request->laundry_item_id,
                'name'                => $request->name,
                'price'               => $request->price,
                'unit_type'           => $request->unit_type,
                'turnaround_time'     => $request->turnaround_time,
            ]);

            return ApiHelper::validResponse('Service item updated successfully!', $serviceItem);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to update service item', 500);
        }
    }

    public function delete_service_item(Request $request, $id)
    {
        $user = $request->user();

        try {
            $serviceItem = ServiceItem::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $serviceItem->delete();

            return ApiHelper::validResponse('Service item deleted successfully!', null);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to delete service item', 500);
        }
    }
}
