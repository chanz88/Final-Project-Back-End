@extends('template')
@section('content')

@auth
<div class="mb-4">
    <span class="fw-bold">Money:</span> Rp. {{ number_format(Auth::user()->money, 0, ',', '.') }}
</div>
@endauth

<div class="mb-4">
    <form method="GET" action="{{ url('/') }}">
        <div class="row">
            <div class="col-md-3">
                <select class="form-select" name="category_id" onchange="this.form.submit()">
                    <option value="">-- All Categories --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

<div class="d-flex gap-2 flex-wrap">
    @forelse ($books as $book)
        <div class="card" style="width: 17rem;">
            @if($book->image_path)
                <a href="{{ route('books.show', $book->id) }}">
                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                        alt="{{ $book->name }}" 
                        class="card-img-top" 
                        style="height: 17rem; object-fit:cover; cursor: pointer;">
                </a>
            @else
                <div class="bg-secondary d-flex align-items-center justify-content-center" 
                     style="height: 17rem;">
                    No Image
                </div>
            @endif
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ route('books.show', $book->id) }}" class="text-decoration-none">
                        {{ $book->name }}
                    </a>
                </h5>
                <p class="card-text">{{ $book->author }}</p>
                <p class="card-text">Rp. {{ number_format($book->price, 0, ',', '.') }}</p>
                <p class="card-text">
                    <small class="text-muted">{{ $book->category->name }}</small>
                </p>

                @auth
                    <div class="row g-2">
                        <div class="col-6">
                            <form action="{{ route('carts.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-success w-100">Add to Cart</button>
                            </form>
                        </div>

                        <div class="col-6">
                            <button type="button" class="btn btn-primary w-100"
                                data-bs-toggle="modal" data-bs-target="#buyNowModal{{ $book->id }}">
                                Buy Now
                            </button>
                        </div>
                    </div>

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
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Login to Buy</a>
                @endauth
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                No books found matching your criteria
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $books->links() }}
</div>

@endsection
