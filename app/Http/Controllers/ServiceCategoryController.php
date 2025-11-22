<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceCategoryController extends Controller
{
    public function get_service_categories(Request $request)
    {
        $user = $request->user();

        $category = ServiceCategory::where('laundry_id', $user->laundry_id)->orderBy('created_at', 'desc')->paginate(20);

        return ApiHelper::validResponse('Service items retrieved successfully!', $category);
    }

    public function get_service_category(Request $request, $id)
    {
        $user = $request->user();

        try {
            $serviceCategory = ServiceCategory::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            return ApiHelper::validResponse('Service category retrieved successfully!', $serviceCategory);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to retrieve service category', 500);
        }
    }

    public function create_service_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'laundry_id'  => 'required|integer',
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $serviceItem = ServiceCategory::create([
                'laundry_id'  => $user->laundry_id,
                'name'        => $request->name,
                'description' => $request->description,
            ]);

            return ApiHelper::validResponse('Service category created successfully!', $serviceItem);
        } catch (\Exception $e) {
            Log::error('Error creating service category: ' . $e->getMessage());
            return ApiHelper::problemResponse('Failed to create service category', 500);
        }
    }

    public function update_service_category(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'laundry_id'  => 'required|integer',
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $serviceItem = ServiceCategory::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $serviceItem->update([
                'laundry_id'  => $user->laundry_id,
                'name'        => $request->name,
                'description' => $request->description,
            ]);

            return ApiHelper::validResponse('Service category updated successfully!', $serviceItem);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to update service category', 500);
        }
    }

    public function delete_service_category(Request $request, $id)
    {
        $user = $request->user();

        try {
            $serviceItem = ServiceCategory::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $serviceItem->delete();

            return ApiHelper::validResponse('Service category deleted successfully!', null);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to delete service category', 500);
        }
    }
}
