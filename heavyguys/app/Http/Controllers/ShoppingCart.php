<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Games;
use App\Models\Purchase;

class ShoppingCart extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;

        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return view('shopping-cart', compact('cart', 'totalPrice'));
    }

    public function addToCart(Request $request, $gameId)
    {
        $game = Games::findOrFail($gameId);
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$gameId])) {
            $cart[$gameId]['quantity']++;
        } else {
            $cart[$gameId] = [
                'name' => $game->name,
                'price' => $game->price,
                'quantity' => 1,
            ];
        }

        $request->session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Game toegevoegd aan winkelwagen!');
    }

    public function removeFromCart(Request $request, $gameId)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$gameId])) {
            unset($cart[$gameId]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function updateQuantity(Request $request, $gameId)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$gameId])) {
            $quantity = $request->input('quantity');
            if ($quantity > 0) {
                $cart[$gameId]['quantity'] = $quantity;
            } else {
                unset($cart[$gameId]);
            }
            $request->session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $gameId => $item) {
            \App\Models\Purchase::create([
                'user_id' => auth()->id(),
                'game_id' => $gameId,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('shopping-cart')->with('success', 'Aankoop geslaagd! Ga nuur weer terug naar de game om het te kunnen downloaden.');
    }

}
