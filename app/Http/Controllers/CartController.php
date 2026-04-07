<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Cloth;
use App\Models\ClothRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'cloth_id' => 'required|exists:clothes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cloth = Cloth::find($request->cloth_id);

        if ($cloth->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available',
            ]);
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('cloth_id', $request->cloth_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'cloth_id' => $request->cloth_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
        ]);
    }

    public function index()
    {
        $cartItems = CartItem::with('cloth')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity;
        });

        return view('user.cart', compact('cartItems', 'total'));
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['success' => true]);
    }

    public function remove($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return response()->json(['success' => true]);
    }

    public function count()
    {
        $count = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json(['count' => $count]);
    }

    public function checkout(Request $request)
    {
        $cartItems = CartItem::with('cloth')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Create requests for each cart item
        foreach ($cartItems as $item) {
            ClothRequest::create([
                'receiver_id' => Auth::id(),
                'cloth_id' => $item->cloth_id,
                'admin_id' => $item->cloth->admin_id,
                'quantity' => $item->quantity,
                'status' => 'pending',
            ]);
        }

        // Clear cart
        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->route('user.my-requests')->with('success', 'Your requests have been submitted!');
    }
}
