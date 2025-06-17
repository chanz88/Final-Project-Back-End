<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
    $query = Book::query();
    
    // Perbaikan logika filter
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    } else {
        // Jika tidak ada filter, tampilkan semua book
        $query->whereNotNull('category_id'); // Atau kosongkan query
    }

    $books = $query->paginate(12);
    $categories = Category::all();

    return view('welcome', compact('books', 'categories'));
    }
}