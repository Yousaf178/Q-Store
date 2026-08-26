<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $total = $subtotal;

        return view('cart.index', compact('cart', 'subtotal', 'total'));
    }

    /**
     * Add a product to the shopping cart.
     */
    public function add(Request $request, Product $product)
    {
        if ($product->quantity <= 0) {
            return back()->with('error', 'Sorry, this item is out of stock.');
        }

        $requestedQty = (int) $request->input('quantity', 1);
        if ($requestedQty < 1) {
            $requestedQty = 1;
        }

        $cart = session()->get('cart', []);

        $currentQtyInCart = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
        $newQty = $currentQtyInCart + $requestedQty;

        if ($newQty > $product->quantity) {
            return back()->with('error', "Cannot add more than available stock ({$product->quantity} available).");
        }

        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'quantity' => $newQty,
            'category' => $product->category->name ?? 'Uncategorized',
            'image_url' => $product->image_url,
            'max_stock' => $product->quantity,
        ];

        session()->put('cart', $cart);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout');
        }

        return back()->with('success', "'{$product->name}' added to your shopping cart!");
    }

    /**
     * Update item quantity in the cart.
     */
    public function update(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$product->id])) {
            return back()->with('error', 'Item not found in cart.');
        }

        $requestedQty = (int) $request->input('quantity', 1);

        if ($requestedQty <= 0) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
            return back()->with('success', 'Item removed from cart.');
        }

        if ($requestedQty > $product->quantity) {
            return back()->with('error', "Only {$product->quantity} units available in stock.");
        }

        $cart[$product->id]['quantity'] = $requestedQty;
        session()->put('cart', $cart);

        return back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear all items from the cart.
     */
    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Shopping cart cleared.');
    }
}
