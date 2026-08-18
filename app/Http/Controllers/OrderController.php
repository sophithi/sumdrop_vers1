<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $items = $request->input('items');

        if (is_string($items)) {
            $items = json_decode($items, true) ?: [];
        }

        $request->merge(['items' => $items]);

        $request->validate([
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit' => ['nullable', 'in:piece,case'],
            'payment_method' => ['required', 'in:cash,bank,mobile'],
        ]);

        $currency = $request->input('currency', 'khr');
        $payment_method = $request->input('payment_method', 'cash');
        $subtotal = 0;

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $saleUnit = $this->resolveSaleUnit($product, $item['unit'] ?? null);
            $price = $product->getPriceForUnit($currency, $saleUnit);
            $subtotal += $price * $item['quantity'];
        }

        // Handle rounding based on currency (KHR: 0 decimals, USD: 2 decimals)
        $decimals = $currency === 'khr' ? 0 : 2;
        $subtotal = round($subtotal, $decimals);
        $tax = 0;
        $total = $subtotal;

        return DB::transaction(function () use ($request, $subtotal, $tax, $total, $currency, $payment_method) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => 'completed',
                'payment_method' => $payment_method,
                'currency' => $currency,
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $saleUnit = $this->resolveSaleUnit($product, $item['unit'] ?? null);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'sale_unit' => $saleUnit,
                    'price' => $product->getPriceForUnit($currency, $saleUnit),
                ]);

                $baseUnitsSold = $item['quantity'] * $product->unitsPerSale($saleUnit);
                $product->update(['stock' => max(0, $product->stock - $baseUnitsSold)]);
            }

            return redirect()->route('orders.show', $order)->with('success', __('common.order_completed'));
        });
    }

    /**
     * Resolve the requested sale unit against what the product actually supports,
     * rather than trusting the client. A non-case product only ever sells as a
     * piece; a case product only sells by the piece if it has a piece price set.
     */
    protected function resolveSaleUnit(Product $product, ?string $requestedUnit): string
    {
        if (! $product->isCase()) {
            return 'piece';
        }

        if ($requestedUnit === 'piece' && $product->sellsByPiece()) {
            return 'piece';
        }

        return 'case';
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('orders.show', compact('order'));
    }

    public function receipts(Request $request)
    {
        $query = Order::with('items.product', 'user');
        
        // Filter by day
        if ($request->filled('day')) {
            $query->whereDay('created_at', $request->day);
        }
        
        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        
        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        
        $receipts = $query->latest()->get();
        
        // Get available years, months, days for filter dropdowns
        $years = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        $months = Order::selectRaw('MONTH(created_at) as month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month');
            
        $days = Order::selectRaw('DAY(created_at) as day')
            ->distinct()
            ->orderBy('day')
            ->pluck('day');

        return view('receipts.index', compact('receipts', 'years', 'months', 'days'));
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', __('common.order_deleted'));
    }
}
