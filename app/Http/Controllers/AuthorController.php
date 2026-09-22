<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateAuthorAction;
use App\Actions\DeleteAuthorAction;
use App\Actions\UpdateAuthorAction;
use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Http\Request;

final class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Author::withCount('books');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $authors = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax() && $request->wantsJson()) {
            return response()->json($authors);
        }

        return view('authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAuthorRequest $request, CreateAuthorAction $action)
    {
        $author = $action->handle($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Author created successfully.',
                'data' => $author,
            ], 201);
        }

        return redirect()->route('authors.index')
            ->with('success', 'Author created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Author $author)
    {
        $author->load('books');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($author);
        }

        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author, UpdateAuthorAction $action)
    {
        $author = $action->handle($author, $request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Author updated successfully.',
                'data' => $author,
            ]);
        }

        return redirect()->route('authors.index')
            ->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Author $author, DeleteAuthorAction $action)
    {
        $action->handle($author);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Author deleted successfully.',
            ]);
        }

        return redirect()->route('authors.index')
            ->with('success', 'Author deleted successfully.');
    }
}
