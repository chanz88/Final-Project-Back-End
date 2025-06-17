@extends('template')
@section('content')
<h1>Shopping Cart</h1>
@if($cartItems->isEmpty())
    <p>Your cart is empty.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Subtotal</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($cartItems as $cartItem)
                @php
                    $subtotal = $cartItem->book->price * $cartItem->quantity;
                    $total += $subtotal;
                @endphp
                <tr>
                    <th scope="row">{{ $cartItem->book->id }}</th>
                    <td>
                        @if($cartItem->book->image_path)
                            <img src="{{ asset('storage/' . $cartItem->book->image_path) }}" alt="Image" style="width: 100px; height: auto;">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $cartItem->book->name }}</td>
                    <td>{{ $cartItem->quantity }}</td>
                    <td>Rp. {{ $cartItem->book->price }}</td>
                    <td>Rp. {{ $subtotal }}</td>
                    <td>
                        <form action="{{ route('carts.destroy', $cartItem->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-end"><strong>Total:</strong></td>
                <td><strong>Rp. {{ $total }}</strong></td>
            </tr>
        </tbody>
    </table>

    <form action="{{ route('checkout') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="address" class="form-label">Delivery Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="postal_code" class="form-label">Postal Code</label>
            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
            @error('postal_code')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Checkout</button>
    </form>
@endif
@endsection
