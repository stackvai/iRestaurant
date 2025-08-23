<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Branch;
use App\Models\Menus\Category as MenusCategory;
use App\Models\Menus\Item as MenusItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class POSController extends Controller
{
    public function index()
    {
        $items = MenusItem::with(['category', 'options.optionItems'])->get();  // ✅ includes options + their items
        $categories = MenusCategory::all();
        $branch = Branch::first(); // or from auth

        return Inertia::render('pos/index', [
            'items' => $items,
            'categories' => $categories,
            'branch' => $branch,
        ]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'payment_method' => 'required|in:cash,card,upi',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(function ($i) {
            return $i['price'] * $i['quantity'];
        });

        $order = Order::create([
            'branch_id' => $validated['branch_id'],
            'payment_method' => $validated['payment_method'],
            'total' => $total,
        ]);

        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return back()->with('success', 'Order created!');
    }
}
