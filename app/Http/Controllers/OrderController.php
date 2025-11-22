<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Order;
use App\Models\OrderServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function get_orders(Request $request)
    {
        $user = $request->user();

        $perPage     = $request->input('per_page', 20);
        $search      = $request->input('search', '');
        $currentPage = $request->input('page', 1);

        $query = Order::with(['items.serviceItem', 'customer'])
            ->where('laundry_id', $user->laundry_id)
            ->orderBy('created_at', 'desc');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($custQuery) use ($search) {
                    $custQuery->where('first_name', 'like', '%' . $search . '%')
                              ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            });
            Log::info('Search applied: ' . $search);
        }

        $orders = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return ApiHelper::validResponse('Orders retrieved successfully!', $orders);
    }

    public function get_order(Request $request, $id)
    {
        $user = $request->user();

        $order = Order::with('items.serviceItem', 'customer')
            ->where('id', $id)
            ->where('laundry_id', $user->laundry_id)
            ->firstOrFail();

        return ApiHelper::validResponse('Order retrieved successfully!', $order);
    }

    public function create_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name'                    => 'required|string|max:255',
            'customer_id'             => 'required|integer',
            'total_amount'            => 'required|numeric|min:0',
            'order_date'              => 'required|date',
            'due_date'                => 'nullable|date|after_or_equal:order_date',
            'status'                  => 'nullable|string|in:pending,processing,ready,delivered',

            'items'                   => 'required|array|min:1',
            'items.*.service_item_id' => 'required|integer',
            'items.*.quantity'        => 'nullable|integer|min:1',
            'items.*.weight'          => 'nullable|numeric|min:0',
            'items.*.price'           => 'required|numeric|min:0',
            'items.*.subtotal'        => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all(),
                'code'   => 422,
            ], 422);
        }

        $user = $request->user();

        DB::beginTransaction();

        try {

            $order = Order::create([
                'name'         => $request->customer_id . ' - ' . now()->timestamp,
                'laundry_id'   => $user->laundry_id,
                'customer_id'  => $request->customer_id,
                'total_amount' => $request->total_amount,
                'order_date'   => $request->order_date ?? now()->toDateString(),
                'due_date'     => $request->due_date,
                'status'       => $request->status ?? 'pending',
            ]);

            foreach ($request->items as $item) {
                OrderServiceItem::create([
                    'name'            => $request->customer_id . ' - Item ' . $item['service_item_id'],
                    'laundry_id'      => $user->laundry_id,
                    'order_id'        => $order->id,
                    'service_item_id' => $item['service_item_id'],
                    'quantity'        => $item['quantity'],
                    'weight'          => $item['weight'],
                    'price'           => $item['price'],
                    'subtotal'        => $item['subtotal'],
                ]);
            }

            DB::commit();

            return ApiHelper::validResponse('Order created successfully!', [
                'order' => $order,
                'items' => $order->serviceItems,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create order: ' . $e->getMessage());
            return ApiHelper::problemResponse('Failed to create order', 500);
        }
    }

    public function update_order(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name'                    => 'required|string|max:255',
            'customer_id'             => 'required|integer',
            'total_amount'            => 'required|numeric|min:0',
            'order_date'              => 'required|date',
            'due_date'                => 'nullable|date|after_or_equal:order_date',
            'status'                  => 'nullable|string|in:pending,processing,ready,delivered',

            'items'                   => 'required|array|min:1',
            'items.*.service_item_id' => 'required|integer',
            'items.*.quantity'        => 'nullable|integer|min:1',
            'items.*.weight'          => 'nullable|numeric|min:0',
            'items.*.price'           => 'required|numeric|min:0',
            'items.*.subtotal'        => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all(),
                'code'   => 422,
            ], 422);
        }

        $user = $request->user();

        DB::beginTransaction();

        try {
            $order = Order::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $order->update([
                'name'         => $order->name,
                'customer_id'  => $request->customer_id,
                'total_amount' => $request->total_amount,
                'order_date'   => $request->order_date,
                'due_date'     => $request->due_date,
                'status'       => $request->status ?? $order->status,
            ]);

            // For simplicity, delete existing items and recreate
            OrderServiceItem::where('order_id', $order->id)->delete();

            foreach ($request->items as $item) {
                OrderServiceItem::create([
                    'name'            => $order->customer_id . ' - Item ' . $item['service_item_id'],
                    'laundry_id'      => $user->laundry_id,
                    'order_id'        => $order->id,
                    'service_item_id' => $item['service_item_id'],
                    'quantity'        => $item['quantity'],
                    'weight'          => $item['weight'],
                    'price'           => $item['price'],
                    'subtotal'        => $item['subtotal'],
                ]);
            }

            DB::commit();

            return ApiHelper::validResponse('Order updated successfully!', [
                'order' => $order,
                'items' => $order->serviceItems,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update order: ' . $e->getMessage());
            return ApiHelper::problemResponse('Failed to update order', 500);
        }
    }

    public function delete_order(Request $request, $id)
    {
        $user = $request->user();

        try {
            $order = Order::where('id', $id)->where('laundry_id', $user->laundry_id)->firstOrFail();
            $order->delete();

            return ApiHelper::validResponse('Order deleted successfully!', null);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to delete order', 500);
        }
    }
}
