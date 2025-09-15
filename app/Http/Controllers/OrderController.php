<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return theme_view('pages.orders.index');
    }

    public function create()
    {
        // Show form to create a new order
    }

    public function store(Request $request)
    {
        // Store a new order
    }

    public function show($id)
    {
        // Display a specific order
    }

    public function edit($id)
    {
        // Show form to edit an order
    }

    public function update(Request $request, $id)
    {
        // Update a specific order
    }

    public function destroy($id)
    {
        // Delete a specific order
    }
}
