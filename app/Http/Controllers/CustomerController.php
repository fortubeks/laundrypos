<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function get_customers(Request $request)
    {
        $user = $request->user();

        // Get query params with defaults
        $perPage     = $request->input('per_page', 20);
        $search      = $request->input('search', '');
        $currentPage = $request->input('page', 1);

        // Build query
        $query = Customer::with(['country', 'state'])->where('laundry_id', $user->laundry_id)
            ->orderBy('created_at', 'desc');

        // Apply search if provided
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('other_names', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Paginate
        $customers = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return ApiHelper::validResponse('Customers retrieved successfully!', $customers);
    }
    
    public function get_customer(Request $request, $id)
    {
        $user = $request->user();

        try {
            $customer = Customer::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            return ApiHelper::validResponse('Customer retrieved successfully!', $customer);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to retrieve customer', 500);
        }
    }

    public function create_customer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:80',
            'first_name'  => 'required|string|max:80',
            'last_name'   => 'nullable|string|max:80',
            'other_names' => 'nullable|string|max:80',
            'email'       => 'nullable|email|max:150',
            'phone_code'  => 'nullable|string|max:10',
            'phone'       => 'nullable|string|max:20',
            'other_phone' => 'nullable|string|max:20',
            'birthday'    => 'nullable|date',
            'address'     => 'nullable|string|max:255',
            'state_id'    => 'nullable|integer',
            'country_id'  => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $customer = Customer::create([
                'laundry_id'  => $user->laundry_id,
                'title'       => $request->title,
                'first_name'  => $request->first_name,
                'last_name'   => $request->last_name,
                'other_names' => $request->other_names,
                'email'       => $request->email,
                'phone_code'  => $request->phone_code,
                'phone'       => $request->phone,
                'other_phone' => $request->other_phone,
                'birthday'    => $request->birthday,
                'address'     => $request->address,
                'state_id'    => $request->state_id,
                'country_id'  => $request->country_id,
            ]);

            return ApiHelper::validResponse('Customer created successfully!', $customer);
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return ApiHelper::problemResponse('Failed to create customer', 500);
        }

    }

    public function update_customer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:80',
            'first_name'  => 'required|string|max:80',
            'last_name'   => 'nullable|string|max:80',
            'other_names' => 'nullable|string|max:80',
            'email'       => 'nullable|email|max:150',
            'phone_code'  => 'nullable|string|max:10',
            'phone'       => 'nullable|string|max:20',
            'other_phone' => 'nullable|string|max:20',
            'birthday'    => 'nullable|date',
            'address'     => 'nullable|string|max:255',
            'state_id'    => 'nullable|integer',
            'country_id'  => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $customer = Customer::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $customer->update([
                'title'       => $request->title,
                'first_name'  => $request->first_name,
                'last_name'   => $request->last_name,
                'other_names' => $request->other_names,
                'email'       => $request->email,
                'phone_code'  => $request->phone_code,
                'phone'       => $request->phone,
                'other_phone' => $request->other_phone,
                'birthday'    => $request->birthday,
                'address'     => $request->address,
                'state_id'    => $request->state_id,
                'country_id'  => $request->country_id,
            ]);

            return ApiHelper::validResponse('Customer updated successfully!', $customer);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to update customer', 500);
        }
    }

    public function delete_customer(Request $request, $id)
    {
        $user = $request->user();

        try {
            $customer = Customer::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $customer->delete();

            return ApiHelper::validResponse('Customer deleted successfully!', null);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to delete customer', 500);
        }
    }

    public function get_countries()
    {
        $countries = DB::table('countries')->orderBy('name')->get();
        return ApiHelper::validResponse('Countries retrieved successfully!', $countries);
    }

    public function get_states($country_id)
    {
        $states = DB::table('states')->where('country_id', $country_id)->orderBy('name')->get();
        return ApiHelper::validResponse('States retrieved successfully!', $states);
    }
}
