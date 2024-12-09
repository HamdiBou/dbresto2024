<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\cartitem;
use App\Models\product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cart $cart)
    {
        //
    }
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $customer = $request->user()->customer;
        $cart = $customer->cart ?? Cart::create([
            'customer_id' => $customer->id,
            'total' => 0
        ]);

        $product = product::findOrFail($validated['product_id']);

        $cartItem = $cart->cartItems()->firstOrNew([
            'product_id' => $product->id
        ]);

        $cartItem->quantity = $cartItem->exists 
            ? $cartItem->quantity + $validated['quantity'] 
            : $validated['quantity'];
        
        $cartItem->cart_id = $cart->id;
        $cartItem->save();

        $total = $cart->cartItems()->join('products', 'cart_items.product_id', '=', 'products.id')
            ->sum(DB::raw('cart_items.quantity * products.price'));

        $cart->update(['total' => $total]);

        return $cart->load('cartItems.product');
    }

    public function removeFromCart(Request $request, $cartItemId)
    {
        $customer = $request->user()->customer;
        $cart = $customer->cart;

        $cartItem = cartitem::where('cart_id', $cart->id)
            ->findOrFail($cartItemId);
        $cartItem->delete();

        $total = $cart->cartItems()->join('products', 'cart_items.product_id', '=', 'products.id')
            ->sum(DB::raw('cart_items.quantity * products.price'));

        $cart->update(['total' => $total]);

        return $cart->load('cartItems.product');
    }

    public function getCart(Request $request)
    {
        $customer = $request->user()->customer;
        $cart = $customer->cart ?? Cart::create([
            'customer_id' => $customer->id,
            'total' => 0
        ]);

        return $cart->load('cartItems.product');
    }
}
