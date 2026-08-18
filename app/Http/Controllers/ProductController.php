<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->pluck('name', 'id');

        return view('products.create', compact('categories'));
    }

    // Absolute path to public_html/storage/products (sibling folder to this app)
    protected function imageUploadPath()
    {
        return base_path('../public_html/storage/products');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price_khr' => ['required', 'numeric', 'min:0'],
            'price_usd' => ['nullable', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:20'],
            'unit' => ['required', 'in:piece,case,can,pack,box,glass'],
            'pack_quantity' => ['nullable', 'required_if:unit,case', 'integer', 'min:1'],
            'price_khr_piece' => ['nullable', 'numeric', 'min:0'],
            'price_usd_piece' => ['nullable', 'numeric', 'min:0'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $destination = $this->imageUploadPath();

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $imagePath = 'products/' . $filename;
        }

        $priceUsd = $validated['price_usd'] ?? round($validated['price_khr'] / 4100, 2);
        $sellsByPiece = $validated['unit'] === 'case' && ! empty($validated['price_khr_piece']);
        $priceUsdPiece = $sellsByPiece
            ? ($validated['price_usd_piece'] ?? round($validated['price_khr_piece'] / 4100, 2))
            : null;

        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'price' => $priceUsd,
            'price_khr' => $validated['price_khr'],
            'price_usd' => $priceUsd,
            'image' => $imagePath,
            'status' => $request->boolean('status'),
            'stock' => $validated['stock'] ?? 0,
            'size' => $validated['size'] ?? null,
            'unit' => $validated['unit'],
            'pack_quantity' => $validated['unit'] === 'case' ? $validated['pack_quantity'] : null,
            'price_khr_piece' => $sellsByPiece ? $validated['price_khr_piece'] : null,
            'price_usd_piece' => $priceUsdPiece,
        ]);

        return redirect()->route('products.index')->with('success', __('common.product_created'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->pluck('name', 'id');

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'price_khr' => ['required', 'numeric', 'min:0'],
            'price_usd' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:20'],
            'unit' => ['required', 'in:piece,case,can,pack,box,glass'],
            'pack_quantity' => ['nullable', 'required_if:unit,case', 'integer', 'min:1'],
            'price_khr_piece' => ['nullable', 'numeric', 'min:0'],
            'price_usd_piece' => ['nullable', 'numeric', 'min:0'],
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            // delete old image file if it exists
            if ($product->image) {
                $oldFile = $this->imageUploadPath() . '/' . basename($product->image);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $destination = $this->imageUploadPath();

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $imagePath = 'products/' . $filename;
        }

        $priceUsd = $validated['price_usd'] ?? round($validated['price_khr'] / 4100, 2);
        $sellsByPiece = $validated['unit'] === 'case' && ! empty($validated['price_khr_piece']);
        $priceUsdPiece = $sellsByPiece
            ? ($validated['price_usd_piece'] ?? round($validated['price_khr_piece'] / 4100, 2))
            : null;

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'price' => $priceUsd,
            'price_khr' => $validated['price_khr'],
            'price_usd' => $priceUsd,
            'image' => $imagePath,
            'status' => $request->boolean('status'),
            'stock' => $validated['stock'] ?? 0,
            'size' => $validated['size'] ?? null,
            'unit' => $validated['unit'],
            'pack_quantity' => $validated['unit'] === 'case' ? $validated['pack_quantity'] : null,
            'price_khr_piece' => $sellsByPiece ? $validated['price_khr_piece'] : null,
            'price_usd_piece' => $priceUsdPiece,
        ]);

        return redirect()->route('products.index')->with('success', __('common.product_updated'));
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            $file = $this->imageUploadPath() . '/' . basename($product->image);
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', __('common.product_deleted'));
    }
}