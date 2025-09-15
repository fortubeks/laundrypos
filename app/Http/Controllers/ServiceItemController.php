<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;
use Illuminate\Http\Request;

class ServiceItemController extends Controller
{
    public function index()
    {
        $serviceItems = ServiceItem::where('laundry_id', laundryId())->paginate(15);
        return theme_view('pages.service-items.index', compact('serviceItems'));
    }

    public function create()
    {
        return theme_view('pages.service-items.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);
        $request->merge(['hotel_id' => laundryId()]);

        LaundryService::create($request->all());

        return redirect()->route('laundry-services.index')->with('success', 'Service created successfully.');
    }

    public function edit(LaundryService $laundryService)
    {
        return view('dashboard.laundry-services.edit', compact('laundryService'));
    }

    public function update(Request $request, LaundryService $laundryService)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        $laundryService->update($request->all());

        return redirect()->route('laundry-services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(LaundryService $laundryService)
    {
        $laundryService->delete();
        return redirect()->route('laundry-services.index')->with('success', 'Service deleted successfully.');
    }
}
