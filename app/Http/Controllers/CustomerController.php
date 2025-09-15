<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('laundry_id', laundryId())->paginate(15);
        return theme_view('pages.customers.index', compact('customers'));
    }

    public function create()
    {
        return theme_view('pages.customers.form');
    }
}
