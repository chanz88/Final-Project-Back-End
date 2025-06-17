<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('book')->get();

        return View::make('cart.index')->with('cartItems', $cartItems);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        $user_id = $request->user_id;
        $book_id = $request->book_id;

        $invoice_id = 'INV-' . strtoupper(Str::random(10));
        while (Cart::where('invoice_id', $invoice_id)->exists()) {
            $invoice_id = 'INV-' . strtoupper(Str::random(10));
        }

        $cartItem = Cart::where('user_id', $user_id)
            ->where('book_id', $book_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user_id,
                'book_id' => $book_id,
                'invoice_id' => $invoice_id,
            ]);
        }

        return redirect()->route('carts.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::find($id);

        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }

        return redirect()->route('carts.index');
    }

    public function destroy($id)
    {
        $cartItem = Cart::find($id);

        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->delete();
        }

        return redirect()->route('carts.index');
    }

    public function generateInvoiceId()
    {
        return 'INV-' . Str::upper(Str::random(10));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string|min:10|max:100',
            'postal_code' => 'required|string|size:5|regex:/^\d{5}$/'
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('book')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('carts.index')->with('error', 'Your cart is empty.');
        }

        $items = $cartItems->map(function ($cartItem) {
            return [
                'book_id' => $cartItem->book_id,
                'name' => $cartItem->book->name,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->book->price,
                'subtotal' => $cartItem->quantity * $cartItem->book->price
            ];
        });

        $totalPrice = $items->sum('subtotal');

        if ($user->money < $totalPrice) {
            return redirect()->route('carts.index')->with('error', 'Insufficient balance to complete purchase.');
        }

        $user->money -= $totalPrice;
        $user->save();

        Invoice::create([
            'user_id' => $user->id,
            'invoice_id' => $this->generateInvoiceId(),
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'total' => $totalPrice,
            'books' => $items->toArray()
        ]);

        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string|min:10|max:100',
            'postal_code' => 'required|string|size:5|regex:/^\d{5}$/',
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($request->book_id);
        $quantity = $request->quantity;
        $totalPrice = $book->price * $quantity;

        if ($user->money < $totalPrice) {
            return redirect()->back()->with('error', 'Insufficient balance to buy this book.');
        }

        $invoice_id = 'INV-' . strtoupper(Str::random(10));
        while (Invoice::where('invoice_id', $invoice_id)->exists()) {
            $invoice_id = 'INV-' . strtoupper(Str::random(10));
        }

        $items = [
            [
                'book_id' => $book->id,
                'name' => $book->name,
                'quantity' => $quantity,
                'price' => $book->price,
                'subtotal' => $totalPrice,
            ],
        ];

        Invoice::create([
            'user_id' => $user->id,
            'invoice_id' => $invoice_id,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'total' => $totalPrice,
            'books' => $items,
        ]);

        $user->money -= $totalPrice;
        $user->save();

        return redirect()->route('invoices.index')->with('success', 'Purchase successful! Invoice created.');
    }

}
