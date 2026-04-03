<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderServiceItem;
use App\Models\Setting;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

        $query = Order::with(['items.serviceItem', 'customer', 'payments'])
            ->where('laundry_id', $user->laundry_id)
            ->orderBy('created_at', 'desc');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($custQuery) use ($search) {
                    $custQuery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            });
        }

        $orders = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return ApiHelper::validResponse('Orders retrieved successfully!', $orders);
    }

    public function get_order(Request $request, $id)
    {
        $user = $request->user();

        $order = Order::with('items.serviceItem', 'customer', 'payments')
            ->where('id', $id)
            ->where('laundry_id', $user->laundry_id)
            ->firstOrFail();

        return ApiHelper::validResponse('Order retrieved successfully!', [
            'order'         => $order,
            'total_payment' => $order->getAmountPaid(),
            'amount_due'    => $order->getOutstandingBalance(),
        ]);
    }

    public function create_order(Request $request)
    {
        $user = $request->user();

        // Check if user has complete business information
        $setting = Setting::where('user_id', $user->user_account_id)->first();

        if (! $setting || ! $setting->isBusinessInfoComplete()) {
            return response([
                'message' => 'Your business information is incomplete. Please complete it in settings before creating orders.',
                'errors'  => ['business_information' => 'Business information must be complete to create orders'],
                'code'    => 422,
            ], 422);
        }

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

            // $this->sendCustomerOrderPlacedNotification($order, $user);

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

    public function sendCustomerOrderPlacedNotification($order, $user)
    {
        try {
            $customer      = Customer::findOrFail($order->customer_id);
            $api_key       = $user->user_account->app_settings->sms_api_key;
            $username      = $user->user_account->app_settings->sms_api_username;
            $sender        = $user->user_account->app_settings->sms_sender;
            $business_name = $user->user_account->app_settings->business_name;
            $msg           = 'Thank you for your order. Your expected order due date is ' . $order->due_date . '. Thank you for choosing' . $business_name;
            $request_url   = 'https://api.ebulksms.com/sendsms?username=' . $username . '&apikey=' . $api_key . '&sender=' . $sender . '&messagetext=' . $msg . '&flash=0&recipients=' . $customer->phone;
            $sms_response  = "";
            if (env('APP_ENV') == 'production') {
                $sms_response = Http::get($request_url);
                sendwhatsappnotification("new_order", $customer->whatsappNumber($user), "order_confirmation", $order->due_date, $order->id);
            }
        } catch (RequestException $exception) {
            // Handle outer exception
            logger($exception);
        }
    }

    public function update_order(Request $request, $id)
    {
        $user = $request->user();

        // Check if user has complete business information
        $setting = Setting::where('user_id', $user->user_account_id)->first();

        if (! $setting || ! $setting->isBusinessInfoComplete()) {
            return response([
                'message' => 'Your business information is incomplete. Please complete it in settings before updating orders.',
                'errors'  => ['business_information' => 'Business information must be complete to update orders'],
                'code'    => 422,
            ], 422);
        }

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

        DB::beginTransaction();

        try {
            $order = Order::where('id', $id)
                ->where('laundry_id', $user->laundry_id)
                ->firstOrFail();

            $order->update([
                'name'         => $order->name,
                'customer_id'  => $request->customer_id,
                'total_amount' => $request->items ? collect($request->items)->sum('subtotal') : $order->total_amount,
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
