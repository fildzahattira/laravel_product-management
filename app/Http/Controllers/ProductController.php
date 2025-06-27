<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

// This controller handles product management functionality
class ProductController extends Controller
{
   public function index(Request $request): View
    {
        $search = $request->input('search');
        $categoryFilter = $request->input('category_filter');

        

        $products = Product::when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($categoryFilter, function ($query, $categoryFilter) {
                return $query->where('category', $categoryFilter);
            })
            ->paginate(10)
            ->withQueryString(); 

        
        $categories = Product::select('category')->distinct()->pluck('category');

        return view('product.index', compact('products', 'search', 'categoryFilter', 'categories'));
    }

    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => 'required|string',
        'category' => 'required|string', 
        'price' => 'required|numeric',
        'stock' => 'required|integer',
    ]);

    Product::create($request->only(['name', 'category', 'price', 'stock']));

    return redirect()->route('products.index')->with('success', 'Product added successfully.');
}


public function update(Request $request, $id): RedirectResponse
{
    // dd($request->all());

    $request->validate([
        'name' => 'required|string',
        'category' => 'required|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
    ]);

    $product = Product::findOrFail($id);
    $product->update($request->only(['name', 'category', 'price', 'stock']));

    return redirect()->route('products.index')->with('success', 'Product updated successfully.');
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('products.index')->with('success', 'Product deleted successfully.');

}
}
