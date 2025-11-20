<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Order;
use App\Models\OrderServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function get_orders(Request $request)
    {
        $user   = $request->user();
        $orders = Order::where('laundry_id', $user->laundry_id)->orderBy('created_at', 'desc')->paginate(20);
        return ApiHelper::validResponse('Orders retrieved successfully!', $orders);
    }

    public function get_order(Request $request, $id)
    {
        $user   = $request->user();
        $order = Order::where('id', $id)->where('laundry_id', $user->laundry_id)->firstOrFail();
        return ApiHelper::validResponse('Order retrieved successfully!', $order);
    }

    public function create_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'customer_id'  => 'required|integer',
            'total_amount' => 'required|numeric|min:0',
            'order_date'   => 'required|date',
            'due_date'     => 'nullable|date|after_or_equal:order_date',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $order = Order::create([
                'name'         => $request->name,
                'laundry_id'   => $user->laundry_id,
                'customer_id'  => $request->customer_id,
                'total_amount' => $request->total_amount,
                'order_date'   => $request->order_date ?? now()->toDateString(),
                'due_date'     => $request->due_date,
                'status'       => $request->status ?? 'pending',
            ]);

            return ApiHelper::validResponse('Order created successfully!', $order);
        } catch (\Exception $e) {
            Log::error('Failed to create order: ' . $e->getMessage());
            return ApiHelper::problemResponse('Failed to create order', 500);
        }
    }

    public function update_order(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'customer_id'  => 'required|integer',
            'total_amount' => 'required|numeric|min:0',
            'order_date'   => 'required|date',
            'due_date'     => 'nullable|date|after_or_equal:order_date',
            'status'       => 'required|in:pending,processing,ready,delivered',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $order = Order::where('id', $id)->where('laundry_id', $user->laundry_id)->firstOrFail();

            $order->update([
                'name'         => $request->name,
                'customer_id'  => $request->customer_id,
                'total_amount' => $request->total_amount,
                'order_date'   => $request->order_date,
                'due_date'     => $request->due_date,
                'status'       => $request->status,
            ]);

            return ApiHelper::validResponse('Order updated successfully!', $order);
        } catch (\Exception $e) {
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

    public function get_orders_service_items(Request $request)
    {
        $user   = $request->user();
        $orders = OrderServiceItem::where('laundry_id', $user->laundry_id)->orderBy('created_at', 'desc')->paginate(20);
        return ApiHelper::validResponse('Orders retrieved successfully!', $orders);
    }

    public function get_order_service_item(Request $request, $id)
    {
        $user   = $request->user();
        $order = OrderServiceItem::where('id', $id)->where('laundry_id', $user->laundry_id)->firstOrFail();
        return ApiHelper::validResponse('Order retrieved successfully!', $order);
    }

    public function create_order_service_item(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'order_id'      => 'required|integer',
            'service_item_id' => 'required|integer',
            'quantity'      => 'nullable|integer|min:1',
            'weight'        => 'nullable|numeric|min:0',
            'price'         => 'required|numeric|min:0',
            'subtotal'      => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $order = OrderServiceItem::create([
                'name'           => $request->name,
                'laundry_id'     => $user->laundry_id,
                'order_id'        => $request->order_id,
                'service_item_id' => $request->service_item_id,
                'quantity'        => $request->quantity,
                'weight'          => $request->weight,
                'price'           => $request->price,
                'subtotal'        => $request->subtotal,
            ]);

            return ApiHelper::validResponse('Order Service Item created successfully!', $order);
        } catch (\Exception $e) {
            Log::error('Failed to create order service item: ' . $e->getMessage());
            return ApiHelper::problemResponse('Failed to create order service item', 500);
        }
    }

    public function update_order_service_item(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'order_id'      => 'required|integer',
            'service_item_id' => 'required|integer',
            'quantity'      => 'nullable|integer|min:1',
            'weight'        => 'nullable|numeric|min:0',
            'price'         => 'required|numeric|min:0',
            'subtotal'      => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user = $request->user();

        try {
            $order = OrderServiceItem::where('id', $id)->where('laundry_id', $user->laundry_id)->firstOrFail();

            $order->update([
                'name'           => $request->name,
                'order_id'        => $request->order_id,
                'service_item_id' => $request->service_item_id,
                'quantity'        => $request->quantity,
                'weight'          => $request->weight,
                'price'           => $request->price,
                'subtotal'        => $request->subtotal,
            ]);

            return ApiHelper::validResponse('Order Service Item updated successfully!', $order);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to update order service item', 500);
        }
    }

    public function delete_order_service_item(Request $request, $id)
    {
        $user = $request->user();

        try {
            $order = OrderServiceItem::where('id', $id)->where('laundry_id', $user->laundry_id)->firstOrFail();
            $order->delete();

            return ApiHelper::validResponse('Order Service Item deleted successfully!', null);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to delete order service item', 500);
        }
    }
}
