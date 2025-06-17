@extends('template')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            @if($book->image_path)
                <img src="{{ asset('storage/' . $book->image_path) }}" 
                     alt="{{ $book->name }}" 
                     class="img-fluid rounded">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                     style="height: 250px;">
                    No Image
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <h2>{{ $book->name }}</h2>
            <p><strong>Author:</strong> {{ $book->author }}</p>
            <p><strong>Publisher:</strong> {{ $book->publisher }}</p>
            <p><strong>Pages:</strong> {{ $book->number_of_page }}</p>
            <p><strong>Category:</strong> {{ $book->category->name }}</p>
            <p><strong>Price:</strong> Rp. {{ number_format($book->price, 0, ',', '.') }}</p>

            @auth
            <div class="d-flex gap-2 mb-3" style="max-width: 300px;">
                <form action="{{ route('carts.store') }}" method="POST" class="flex-grow-1">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-success w-100" style="height: 42px;">Add to Cart</button>
                </form>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary flex-grow-1" style="height: 42px;" data-bs-toggle="modal" data-bs-target="#buyNowModal{{ $book->id }}">
                    Buy Now
                </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="buyNowModal{{ $book->id }}" tabindex="-1" aria-labelledby="buyNowModalLabel{{ $book->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('buy-now') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="buyNowModalLabel{{ $book->id }}">Confirm Purchase</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Shipping Address</label>
                                    <textarea name="address" class="form-control" rows="2" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" name="postal_code" class="form-control" maxlength="5" pattern="\d{5}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Confirm</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary mb-3">Login to Buy</a>
            @endauth

            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
