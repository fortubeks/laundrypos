<?php

use App\Models\Customer;
use App\Models\LaundryItem;
use App\Models\Order;
use App\Models\ServiceCategory;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;

if (! function_exists('activeClass')) {
    function activeClass($route, $activeClass = 'active')
    {
        return request()->routeIs($route) ? $activeClass : '';
    }
}

if (! function_exists('theme_view')) {
    function theme_view($view, $data = [], $mergeData = [])
    {
        $theme = config('theme.active');
        $path  = $theme . '.' . $view;

        if (view()->exists($path)) {
            return view($path, $data, $mergeData);
        }

        // fallback to default theme or flat view
        return view($view, $data, $mergeData);
    }
}

if (! function_exists('arrayToObject')) {
    function arrayToObject($d)
    {
        if (is_array($d)) {
            /*
      * Return array converted to object
      * Using __FUNCTION__ (Magic constant)
      * for recursive call
      */
            return (object) array_map(__FUNCTION__, $d);
        } else {
            // Return object
            return $d;
        }
    }
}

function laundryId()
{
    $user = auth()->user();

    if (! $user) {
        abort(401, 'Unauthorized: User not authenticated.');
    }

    if (! $user->laundry_id) {
        abort(403, 'Forbidden: User has no associated laundry.');
    }

    return $user->laundry_id;
}

function getModelList($model)
{
    $user       = auth()->user();
    $laundry    = $user->laundry;
    $laundry_id = $user->laundry_id;

    return match ($model) {
        'countries'             => DB::select('select id, name from countries'),
        'states'                => DB::table('states')->where('country_id', 161)->orderBy('name')->get(),
        'expense-categories'    => ExpenseCategory::whereIn('laundry_id', [0, $laundry_id])->orderBy('name')->get(),
        'suppliers'             => Supplier::where('laundry_id', $laundry_id)->get(),
        'purchases'             => Purchase::where('store_id', $laundry->store->id)->get(),
        'expense-items'         => ExpenseItem::where('laundry_id', $laundry_id)->orderBy('name')->get(),
        'store-items'           => StoreItem::where('laundry_id', $laundry->id)->orderBy('name')->get(),
        'purchase-categories'   => PurchaseCategory::where('laundry_id', $laundry_id)->orderBy('name')->get(),
        'store-item-categories' => StoreItemCategory::where('laundry_id', $laundry->id)->orderBy('name')->get(),
        'customers'             => Customer::where('laundry_id', $laundry_id)->orderBy('first_name')->get(),
        'laundry-items'         => LaundryItem::where('laundry_id', $laundry_id)->orderBy('name')->get(),
        'service-categories'    => ServiceCategory::whereIn('laundry_id', [0, $laundry_id])->orderBy('name')->get(),
        'service-items'         => ServiceItem::where('laundry_id', $laundry_id)
            ->with(['laundryItem', 'serviceCategory'])
            ->orderBy('name')
            ->get(),
        'orders'                => Order::where('laundry_id', $laundry_id)->orderBy('created_at', 'desc')->get(),

        'bank-accounts'         => BankAccount::where('laundry_id', $laundry_id)->orderBy('account_name')->get(),
        'companies'             => Company::where('laundry_id', $laundry_id)->orderBy('name')->get(),

        'genders'               => ['Female' => 'female', 'Male' => 'male'],
        'delivery-areas'        => DeliveryArea::where('state_id', $laundry->state_id)->orderBy('name')->get(),
        'unit-types'            => ['Per item' => 'per_item', 'Per KG' => 'per_kg'],

        default                 => null,
    };
}

function removeSpaces($inputString)
{
    return str_replace(' ', '', $inputString);
}

function sendwhatsappnotification($type, $to, $template_name, $due_date, $order_id)
{
    try {
        $url = "https://graph.facebook.com/v17.0/108752848993090/messages";

        $client  = new \GuzzleHttp\Client();
        $headers = ["Authorization" => "Bearer " . env('WHATSAPP_TOKEN'), "Content-Type" => "application/json"];
        $params  = [
            "messaging_product" => "whatsapp",
            "to"                => $to,
            "type"              => "template",
            "template"          => ["name" => $template_name, "language" => ["code" => "en_US"]],
        ];

        if ($type == "new_order") {
            $arr = [
                "type" => "text",
                "text" => $due_date,
            ];
            $arr1 = [
                "type" => "text",
                "text" => auth()->user()->user_account->app_settings->business_name,
            ];
            $arr2 = [
                "type" => "text",
                "text" => "/" . $order_id,
            ];

            $myJSON  = json_encode($arr);
            $myJSON2 = json_encode($arr1);
            $myJSON3 = json_encode($arr2);

            $params = [
                "messaging_product" => "whatsapp",
                "to"                => $to,
                "type"              => "template",
                "template"          => [
                    "namespace"  => "46e87f57_c607_4e9f_b8f5_a8700ba35edb",
                    "language"   => [
                        "code" => "en_US",
                    ],
                    "name"       => $template_name,
                    "components" => [

                        [
                            "type"       => "body",
                            "parameters" => [
                                $myJSON,
                                $myJSON2,
                            ],
                            # end body
                        ],

                        # The following part of this code example includes several possible button types,
                        # not all are required for an interactive message template API call.

                        [
                            "type"       => "button",
                            "sub_type"   => "url",
                            "index"      => "0",
                            "parameters" => [
                                $myJSON3,
                            ],
                        ],

                    ],
                ],
            ];
        }

        $client   = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "form_params" => $params]);

        $data = $response->getBody();
        logger($data);
        return $data;
    } catch (Exception $ex) {
        logger($ex->getMessage());
        return null;
    }
}
