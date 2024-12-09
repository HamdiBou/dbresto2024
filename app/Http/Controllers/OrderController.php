<?php

namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->customer->orders()->with('orderItems.product')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = $request->user()->customer->cart;
        
        $order = Order::create([
            'customer_id' => $request->user()->customer->id,
            'order_date' => now(),
            'total_amount' => $cart->total,
            'status' => 'pending'
        ]);

        foreach ($cart->cartItems as $cartItem) {
            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->price
            ]);
        }

        $cart->cartItems()->delete();
        $cart->update(['total' => 0]);

        return $order->load('orderItems.product');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }
}
