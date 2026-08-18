<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->get();
        $categories = $products->pluck('category')->filter()->unique('id')->sortBy('name')->values();

        $outOfStockCount = $products->filter(fn (Product $p) => $p->isOutOfStock())->count();
        $lowStockCount = $products->filter(fn (Product $p) => $p->isLowStock() && ! $p->isOutOfStock())->count();

        return view('stock.index', compact('products', 'categories', 'lowStockCount', 'outOfStockCount'));
    }

    /**
     * JSON snapshot of current stock levels, polled by the live view.
     */
    public function data()
    {
        $products = Product::orderBy('name')->get()->map(function (Product $product) {
            return [
                'id' => $product->id,
                'stock' => $product->stock,
                'is_low' => $product->isLowStock(),
            ];
        });

        return response()->json(['products' => $products]);
    }

    public function adjust(Request $request, Product $product)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:increment,decrement,set'],
            'amount' => ['required', 'integer', 'min:0'],
        ]);

        $stock = match ($validated['action']) {
            'increment' => $product->stock + $validated['amount'],
            'decrement' => max(0, $product->stock - $validated['amount']),
            'set' => $validated['amount'],
        };

        $product->update(['stock' => $stock]);

        return response()->json([
            'id' => $product->id,
            'stock' => $product->stock,
            'is_low' => $product->isLowStock(),
        ]);
    }
}
