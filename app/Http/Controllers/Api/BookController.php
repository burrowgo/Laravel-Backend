<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Example: Eager load user if you display book owner info
        // $books = Book::with('user')->get();
        // return response()->json($books);
        return response()->json(['message' => 'Endpoint for viewing all books. Accessible by users with "view books" permission.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Example validation (adjust as needed)
        // $validated = $request->validate([
        //     'title' => 'required|string|max:255',
        //     'author' => 'required|string|max:255',
        // ]);
        // $book = Book::create([
        //     'title' => $validated['title'],
        //     'author' => $validated['author'],
        //     'user_id' => auth()->id(), // Associate with the authenticated user
        // ]);
        // return response()->json($book, 201);
        return response()->json(['message' => 'Endpoint for creating a new book. Accessible by users with "create books" permission.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // return response()->json($book);
        return response()->json(['message' => "Endpoint for viewing book #{$book->id}. Accessible by users with \"view books\" permission."]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        // Example validation (adjust as needed)
        // $validated = $request->validate([
        //     'title' => 'sometimes|required|string|max:255',
        //     'author' => 'sometimes|required|string|max:255',
        // ]);
        // $book->update($validated);
        // return response()->json($book);
        return response()->json(['message' => "Endpoint for updating book #{$book->id}. Accessible by users with \"edit books\" permission."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // $book->delete();
        // return response()->json(null, 204);
        return response()->json(['message' => "Endpoint for deleting book #{$book->id}. Accessible by users with \"delete books\" permission."]);
    }
}
