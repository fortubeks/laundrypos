<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // get analytics data for dashboard by period (today, this week, this month, this year)
    // public function get_analytics(Request $request, $period = 'this_month')
    // {
    //     $user = $request->user();

    //     $laundry_id = $user->laundry_id;

    //     // get data based on period
    //     $total_orders    = Order::where('laundry_id', $laundry_id)->wh;
    //     $total_revenue   = Order::where('laundry_id', $laundry_id)->sum('total_amount');
    //     $total_customers = Customer::where('laundry_id', $laundry_id)->count();
    //     $pending_orders  = Order::where('laundry_id', $laundry_id)->where('status', 'pending')->count();

    //     return ApiHelper::validResponse('Dashboard analytics retrieved successfully!', [
    //         'analytics' => [
    //             'total_orders'    => $total_orders,
    //             'total_revenue'   => $total_revenue,
    //             'total_customers' => $total_customers,
    //             'pending_orders'  => $pending_orders,
    //         ],
    //     ]);
    // }
    private function getDateRanges($period)
    {
        switch ($period) {

            case 'today':
                return [
                    'current'  => [now()->startOfDay(), now()->endOfDay()],
                    'previous' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
                ];

            case 'week':
                return [
                    'current'  => [now()->startOfWeek(), now()->endOfWeek()],
                    'previous' => [
                        now()->subWeek()->startOfWeek(),
                        now()->subWeek()->endOfWeek(),
                    ],
                ];

            case 'month':
                return [
                    'current'  => [now()->startOfMonth(), now()->endOfMonth()],
                    'previous' => [
                        now()->subMonth()->startOfMonth(),
                        now()->subMonth()->endOfMonth(),
                    ],
                ];

            case 'year':
                return [
                    'current'  => [now()->startOfYear(), now()->endOfYear()],
                    'previous' => [
                        now()->subYear()->startOfYear(),
                        now()->subYear()->endOfYear(),
                    ],
                ];

            case 'all':
            default:
                return [
                    'current'  => [null, null],
                    'previous' => [null, null],
                ];
        }
    }
    private function getMetrics($laundry_id, $range)
    {
        [$start, $end] = $range;

        $orders    = Order::where('laundry_id', $laundry_id);
        $customers = Customer::where('laundry_id', $laundry_id);

        if ($start && $end) {
            $orders->whereBetween('created_at', [$start, $end]);
            $customers->whereBetween('created_at', [$start, $end]);
        }

        return [
            'total_orders'    => $orders->count(),
            'total_revenue'   => $orders->sum('total_amount'),
            'total_customers' => $customers->count(),
            'pending_orders'  => $orders->where('status', 'pending')->count(),
        ];
    }

    private function calculatePercentageChanges($current, $previous)
    {
        $result = [];

        foreach ($current as $key => $currentValue) {
            $previousValue = $previous[$key] ?? 0;

            if ($previousValue == 0) {
                $percentage = $currentValue > 0 ? 100 : 0;
            } else {
                $percentage = (($currentValue - $previousValue) / $previousValue) * 100;
            }

            $result[$key] = round($percentage, 2);
        }

        return $result;
    }

    public function get_analytics(Request $request)
    {
        $user       = $request->user();
        $laundry_id = $user->laundry_id;

        $period = $request->query('period', 'all'); // default "all"

        // Resolve date ranges
        $ranges        = $this->getDateRanges($period);
        $currentRange  = $ranges['current'];
        $previousRange = $ranges['previous'];

        // Get metrics
        $current  = $this->getMetrics($laundry_id, $currentRange);
        $previous = $this->getMetrics($laundry_id, $previousRange);

        // Calculate % differences
        $percentage = $this->calculatePercentageChanges($current, $previous);

        return ApiHelper::validResponse('Dashboard analytics retrieved successfully!', [
            'analytics' => [
                'current'    => $current,
                'previous'   => $previous,
                'percentage' => $percentage,
            ],
        ]);
    }

}
