@extends('template')
@section('content')
    <form action="{{route('books.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="book-name" class="form-label">Book Name</label>
            <input type="text" class="form-control" id="book-name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="book-author" class="form-label">Author</label>
            <input type="text" class="form-control" id="book-author" name="author" value="{{ old('author') }}">
            @error('author')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="book-publisher" class="form-label">Publisher</label>
            <input type="text" class="form-control" id="book-publisher" name="publisher" value="{{ old('publisher') }}">
            @error('publisher')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="book-pages" class="form-label">Number of Pages</label>
            <input type="number" class="form-control" id="book-pages" name="number_of_page" min="1" value="{{ old('number_of_page') }}">
            @error('number_of_page')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="book-price" class="form-label">Price (Rupiah)</label>
            <input type="number" class="form-control" id="book-price" name="price" min="0" value="{{ old('price') }}">
            @error('price')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category_id">
                @foreach ($categories as $category)
                    <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{$category->name}}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="formFile" class="form-label">Book Cover Image</label>
            <input class="form-control" type="file" id="formFile" name="image_path" accept="image/png, image/jpeg, image/jpg">
            @error('image_path')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection