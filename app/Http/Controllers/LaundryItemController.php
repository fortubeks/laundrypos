<?php

namespace App\Http\Controllers;

use App\Models\LaundryItem;
use Illuminate\Http\Request;

class LaundryItemController extends Controller
{
    public function index()
    {
        $laundryItems = LaundryItem::where('laundry_id', laundryId())->paginate(15);
        return theme_view('pages.laundry-items.index', compact('laundryItems'));
    }

    public function create()
    {
        return theme_view('pages.laundry-items.form');
    }
}
