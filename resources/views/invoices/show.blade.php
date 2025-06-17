@extends('template')
@section('content')
<h1>Invoice Details</h1>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Quantity</th>
            <th scope="col">Price</th>
            <th scope="col">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice->books as $book)
            <tr>
                <td>{{ $book['name'] }}</td>
                <td>{{ $book['quantity'] }}</td>
                <td>Rp. {{ $book['price'] }}</td>
                <td>Rp. {{ $book['subtotal'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="text-end"><strong>Total:</strong></td>
            <td><strong>Rp. {{ $invoice->total }}</strong></td>
        </tr>
    </tbody>
</table>
@endsection
