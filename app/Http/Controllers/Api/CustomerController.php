<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->laundry_id) {
            return response()->json(['message' => 'No laundry associated with user'], 403);
        }

        $customers = Customer::where('laundry_id', $user->laundry_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($customers);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->laundry_id) {
            return response()->json(['message' => 'No laundry associated with user'], 403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'other_names' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:20'],
            'other_phone' => ['nullable', 'string', 'max:20'],
            'birthday' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'state_id' => ['nullable', 'integer'],
            'country_id' => ['nullable', 'integer'],
        ]);

        $validated['laundry_id'] = $user->laundry_id;

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->laundry_id) {
            return response()->json(['message' => 'No laundry associated with user'], 403);
        }

        $customer = Customer::where('laundry_id', $user->laundry_id)
            ->findOrFail($id);

        return response()->json($customer);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->laundry_id) {
            return response()->json(['message' => 'No laundry associated with user'], 403);
        }

        $customer = Customer::where('laundry_id', $user->laundry_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'other_names' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'phone' => ['sometimes', 'required', 'string', 'max:20'],
            'other_phone' => ['nullable', 'string', 'max:20'],
            'birthday' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'state_id' => ['nullable', 'integer'],
            'country_id' => ['nullable', 'integer'],
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->laundry_id) {
            return response()->json(['message' => 'No laundry associated with user'], 403);
        }

        $customer = Customer::where('laundry_id', $user->laundry_id)
            ->findOrFail($id);

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}

