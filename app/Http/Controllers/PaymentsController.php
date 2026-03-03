<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{

    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);

        $allPayments = Payment::query()->where('user_id', $request->user()->id)->orderBy('created_at', 'desc');

        $allPayments->when($request->mode, function ($query, $filterBy) {
            return $query->where('mode_of_payment', '=', $filterBy);
        });

        $allPayments->when($request->start_date, function ($query, $start_date) {
            return $query->where('created_at', '>=', $start_date);
        })->when($request->end_date, function ($query, $end_date) {
            return $query->where('created_at', '<=', $end_date);
        });

        $payments = $allPayments->with('invoice.order.customer')
            ->paginate($per_page);

        return ApiHelper::validResponse('Payments retrieved successfully', $payments);
    }

    public function store(Request $request)
    {
        //validate customer entry, store and set view to list of customers
        $validatedData = $request->validate([
            'order_id'        => ['required', 'exists:orders,id'],
            'mode_of_payment' => 'required',
            'amount'          => 'required',
        ]);

        $order = Order::findOrFail($request->order_id);
        Log::info('User:' . json_encode($request->user()) . ' is adding payment for order:' . $order->id);
        $payment                  = new Payment;
        $payment->user_id         = $request->user()->user_account_id;
        $payment->order_id        = $order->id;
        $payment->amount          = $request->amount;
        $payment->mode_of_payment = $request->mode_of_payment;
        $payment->notes           = $request->notes;

        $status = "Processing";

        //update the order status
        $totalPayments = Payment::where('order_id', $order->id)->sum('amount') + $request->amount;
        if ($totalPayments >= $order->total_amount) {
            $status = "Completed";
        } elseif ($totalPayments > 0 && $totalPayments < $order->total_amount) {
            $status = "Processing";
        } else {
            $status = "Pending";
        }

        $affected = DB::update('update orders set status = ? where id = ?', [$status, $order->id]);

        $payment->save();

        return ApiHelper::validResponse('Payment created successfully!', $payment);
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);

        $order = Order::find($payment->order_id);
        //delete the payment
        $payment->delete();
        //update the order status
        $totalPayments = Payment::where('order_id', $order->id)->sum('amount');
        if ($totalPayments >= $order->total_amount) {
            $order->status = "Completed";
        } elseif ($totalPayments > 0 && $totalPayments < $order->total_amount) {
            $order->status = "Processing";
        } else {
            $order->status = "Pending";
        }
        $order->save();

        return ApiHelper::validResponse('Payment deleted successfully!', null);
    }
}
