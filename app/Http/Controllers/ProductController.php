<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = [
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = [
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('image')) {
            // Delete existing image if stored locally
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function details(Product $product)
    {
        $product->load('category');
        
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                if ($product->brand) {
                    $query->where('brand', $product->brand)
                          ->orWhere('category_id', $product->category_id);
                } else {
                    $query->where('category_id', $product->category_id);
                }
            })
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.user_show', compact('product', 'relatedProducts'));
    }

    public function purchase(Request $request, Product $product)
    {
        if ($product->quantity <= 0) {
            return back()->with('error', 'Sorry, this product is currently out of stock.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity,
        ], [
            'quantity.max' => 'You cannot purchase more than the available stock (' . $product->quantity . ').',
        ]);

        $qtyPurchased = (int) $request->quantity;
        $totalPrice = $product->price * $qtyPurchased;

        $product->decrement('quantity', $qtyPurchased);

        return redirect()->route('dashboard')
            ->with('success', "🎉 Purchase successful! You bought {$qtyPurchased}x '{$product->name}' for $" . number_format($totalPrice, 2) . ".");
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}