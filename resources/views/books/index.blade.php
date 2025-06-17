@extends('template')
@section('content')
    <div class="mb-4">
        <a href="{{route('books.create')}}" class="btn btn-success">Add New Book</a>

        <form method="GET" action="{{ route('books.index') }}" class="mt-3">
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

    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col">Author</th>
            <th scope="col">Publisher</th>
            <th scope="col">Pages</th>
            <th scope="col">Price</th>
            <th scope="col">Category</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <th scope="row">{{$book->id}}</th>
                    <td>
                        @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}" 
                                 alt="Book Cover" 
                                 style="width: 80px; height: auto; object-fit: cover;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>{{$book->name}}</td>
                    <td>{{$book->author}}</td>
                    <td>{{$book->publisher}}</td>
                    <td>{{$book->number_of_page}}</td>
                    <td>Rp {{ number_format($book->price, 0, ',', '.') }}</td>
                    <td>{{$book->category->name}}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{route('books.edit', $book->id)}}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{route('books.destroy', $book->id)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="alert alert-info">
                            No books found matching your criteria
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
@endsection