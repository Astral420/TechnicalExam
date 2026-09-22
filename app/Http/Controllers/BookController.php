<?php

namespace App\Http\Controllers;

use App\Actions\CreateBookAction;
use App\Actions\DeleteBookAction;
use App\Actions\UpdateBookAction;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('author');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        $authors = Author::orderBy('name')->get();

        if ($request->ajax() && $request->wantsJson()) {
            return response()->json($books);
        }

        return view('books.index', compact('books', 'authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::orderBy('name')->get();

        return view('books.create', compact('authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request, CreateBookAction $action)
    {
        $book = $action->handle($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Book created successfully.',
                'data' => $book->load('author'),
            ], 201);
        }

        return redirect()->route('books.index')
            ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Book $book)
    {
        $book->load('author');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($book);
        }

        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $book->load('author');
        $authors = Author::orderBy('name')->get();

        return view('books.edit', compact('book', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book, UpdateBookAction $action)
    {
        $book = $action->handle($book, $request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully.',
                'data' => $book->load('author'),
            ]);
        }

        return redirect()->route('books.index')
            ->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Book $book, DeleteBookAction $action)
    {
        $action->handle($book);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully.',
            ]);
        }

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
