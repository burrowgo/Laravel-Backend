<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BookService
{
    /**
     * Create a new Book record.
     *
     * @param array $data
     * @return Book
     * @throws ValidationException
     */
    public function createBook(array $data): Book
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|max:255|unique:books,isbn',
            // Add other book-specific fields here as needed
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return Book::create($validator->validated());
    }

    /**
     * Update an existing Book record.
     *
     * @param Book $book
     * @param array $data
     * @return Book
     * @throws ValidationException
     */
    public function updateBook(Book $book, array $data): Book
    {
        $validator = Validator::make($data, [
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|max:255|unique:books,isbn,' . $book->id,
            // Add other book-specific fields here as needed
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $book->update($validator->validated());
        return $book->fresh();
    }

    // Add other methods like deleteBook, findBook etc. as needed.
}
