@extends('layouts.app', ['title' => $author->name . ' — Author Details'])

@section('content')
<div class="space-y-8 max-w-4xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-[13px] text-[#6B5D45]">
        <a href="{{ route('authors.index') }}" class="hover:text-[#3D3428] inline-flex items-center gap-1 transition-colors">
            <x-bi-arrow-left class="w-4 h-4" />
            <span>Back to Authors</span>
        </a>
        <span>/</span>
        <span class="text-[#3D3428] font-medium truncate max-w-xs">{{ $author->name }}</span>
    </div>

    <!-- Author Profile Card -->
    <div class="bg-[#FFFDF7] border border-[#D4C5A9] rounded-lg p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-6 border-b border-[#D4C5A9]">
            <div>
                <span class="text-[11px] font-semibold text-[#6B5D45] uppercase tracking-wider block mb-1">Author Entry #{{ $author->id }}</span>
                <h1 class="text-page-title text-[#3D3428]">{{ $author->name }}</h1>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('authors.edit', $author) }}"
                   class="px-3.5 py-2 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors inline-flex items-center gap-1.5">
                    <x-bi-pencil class="w-3.5 h-3.5" />
                    <span>Edit</span>
                </a>
                <form action="{{ route('authors.destroy', $author) }}" method="POST" onsubmit="return confirm('Are you sure? This will also remove all books by this author.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-3.5 py-2 text-[13px] font-semibold bg-[#B44D3B] text-white rounded-md hover:bg-[#9E3F2F] transition-colors cursor-pointer inline-flex items-center gap-1.5">
                        <x-bi-trash class="w-3.5 h-3.5" />
                        <span>Delete</span>
                    </button>
                </form>
            </div>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6">
            <div>
                <dt class="text-column-ledger">Birth Date</dt>
                <dd class="mt-1 text-[15px] text-[#3D3428] font-medium">
                    {{ $author->birth_date?->format('F d, Y') ?? 'N/A' }}
                </dd>
            </div>

            <div>
                <dt class="text-column-ledger">Total Publications</dt>
                <dd class="mt-1 text-[15px] text-[#3D3428] font-semibold">
                    {{ $author->books->count() }} {{ Str::plural('book', $author->books->count()) }}
                </dd>
            </div>

            <div>
                <dt class="text-column-ledger">Recorded Since</dt>
                <dd class="mt-1 text-[13px] text-[#6B5D45]">
                    {{ $author->created_at?->format('M d, Y') ?? 'N/A' }}
                </dd>
            </div>
        </dl>
    </div>

    <!-- Books by Author Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-section">Books by {{ $author->name }}</h2>
            <a href="{{ route('books.create') }}"
               class="bg-[#C9A84C] text-[#3D3428] font-semibold text-[13px] py-2 px-3.5 rounded-md hover:bg-[#B8973E] transition-colors inline-flex items-center gap-1.5">
                <x-bi-plus-lg class="w-3.5 h-3.5" />
                <span>Add Book for this Author</span>
            </a>
        </div>

        <div class="bg-[#FFFDF7] border border-[#D4C5A9] rounded-lg overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#B8A88A]">
                        <th scope="col" class="py-3 px-4 text-column-ledger">Title</th>
                        <th scope="col" class="py-3 px-4 text-column-ledger">Published Date</th>
                        <th scope="col" class="py-3 px-4 text-column-ledger text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D4C5A9]">
                    @forelse($author->books as $book)
                        <tr class="text-[15px] hover:bg-[#C9A84C]/10 transition-colors">
                            <td class="py-3 px-4">
                                <a href="{{ route('books.show', $book) }}" class="italic text-[#3D3428] font-medium hover:text-[#C9A84C] transition-colors">
                                    {{ $book->title }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-[13px] text-[#6B5D45]">
                                {{ $book->published_date?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap text-[13px] space-x-3">
                                <a href="{{ route('books.show', $book) }}" class="text-[#6B5D45] hover:text-[#3D3428] font-medium transition-colors">
                                    View
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="text-[#6B5D45] hover:text-[#3D3428] font-medium transition-colors">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 px-4 text-center text-[#9C8E76] text-[14px]">
                                No publications recorded for this author yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
