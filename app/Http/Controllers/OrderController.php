<?php

namespace App\Http\Controllers;
use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')->latest()->paginate(10);
        return view('order', compact('orders'));
    }



public function receiveFromHub(Request $request)
{
    // Validasi struktur data dasar
    $validated = $request->validate([
        'order_number' => 'required|string',
        'total_amount' => 'required|numeric',
        'shipping_address' => 'required|string',
        'shipping_city' => 'required|string',
        'shipping_province' => 'required|string',
        'shipping_postal_code' => 'required|string',
        'notes' => 'nullable|string',
        'items' => 'required|array',
        'items.*.product_id' => 'required|integer',
        'items.*.quantity' => 'required|integer',
        'items.*.price' => 'required|numeric',
    ]);

    // Simpan ke database
    $order = \App\Models\Order::create([
        'order_number' => $validated['order_number'],
        'total_amount' => $validated['total_amount'],
        'shipping_address' => $validated['shipping_address'],
        'shipping_city' => $validated['shipping_city'],
        'shipping_province' => $validated['shipping_province'],
        'shipping_postal_code' => $validated['shipping_postal_code'],
        'notes' => $validated['notes'] ?? null,
    ]);

    foreach ($validated['items'] as $item) {
        $order->items()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    return response()->json(['message' => 'Pesanan berhasil diterima'], 201);
}
}
