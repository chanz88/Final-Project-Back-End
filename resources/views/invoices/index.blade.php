@extends('template')
@section('content')
<h1>Invoices</h1>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Invoice ID</th>
            <th scope="col">Date</th>
            <th scope="col">Total</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_id }}</td>
                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                <td>Rp. {{ $invoice->total }}</td>
                <td>
                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-primary">View</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
