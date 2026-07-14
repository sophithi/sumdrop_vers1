<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('user')->latest()->get();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        return view('purchases.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier' => ['nullable', 'string', 'max:255'],
            'total' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'in:usd,khr'],
            'payment_method' => ['required', 'in:cash,bank,mobile'],
            'note' => ['nullable', 'string'],
        ]);

        Purchase::create([
            'user_id' => $request->user()->id,
            'supplier' => $request->input('supplier'),
            'total' => $request->input('total'),
            'currency' => $request->input('currency'),
            'payment_method' => $request->input('payment_method'),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded successfully.');
    }
}
