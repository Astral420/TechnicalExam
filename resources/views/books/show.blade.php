@extends('layouts.app', ['title' => $book->title . ' — Book Details'])

@section('content')
<div class="space-y-6 max-w-3xl">
    <!-- Breadcrumb / Back Navigation -->
    <div class="flex items-center gap-2 text-[13px] text-[#6B5D45]">
        <a href="{{ route('books.index') }}" class="hover:text-[#3D3428] inline-flex items-center gap-1 transition-colors">
            <x-bi-arrow-left class="w-4 h-4" />
            <span>Back to Books</span>
        </a>
        <span>/</span>
        <span class="text-[#3D3428] font-medium truncate max-w-xs">{{ $book->title }}</span>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-[#FFFDF7] border border-[#D4C5A9] rounded-lg p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-6 border-b border-[#D4C5A9]">
            <div>
                <span class="text-[11px] font-semibold text-[#6B5D45] uppercase tracking-wider block mb-1">Book Entry #{{ $book->id }}</span>
                <h1 class="text-page-title italic text-[#3D3428]">{{ $book->title }}</h1>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('books.edit', $book) }}"
                   class="px-3.5 py-2 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors inline-flex items-center gap-1.5">
                    <x-bi-pencil class="w-3.5 h-3.5" />
                    <span>Edit</span>
                </a>
                <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book entry?');">
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

        <!-- Ledger Data Fields -->
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6">
            <div>
                <dt class="text-column-ledger">Author</dt>
                <dd class="mt-1 text-[15px] text-[#3D3428]">
                    @if($book->author)
                        <a href="{{ route('authors.show', $book->author) }}" class="text-[#3D3428] font-semibold hover:text-[#C9A84C] underline underline-offset-2 transition-colors">
                            {{ $book->author->name }}
                        </a>
                    @else
                        <span class="text-[#9C8E76] italic">Unassigned</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-column-ledger">Published Date</dt>
                <dd class="mt-1 text-[15px] text-[#3D3428] font-medium">
                    {{ $book->published_date?->format('F d, Y') ?? 'N/A' }}
                </dd>
            </div>

            <div>
                <dt class="text-column-ledger">Recorded On</dt>
                <dd class="mt-1 text-[13px] text-[#6B5D45]">
                    {{ $book->created_at?->format('M d, Y · h:i A') ?? 'N/A' }}
                </dd>
            </div>

            <div>
                <dt class="text-column-ledger">Last Updated</dt>
                <dd class="mt-1 text-[13px] text-[#6B5D45]">
                    {{ $book->updated_at?->format('M d, Y · h:i A') ?? 'N/A' }}
                </dd>
            </div>
        </dl>
    </div>
</div>
@endsection
