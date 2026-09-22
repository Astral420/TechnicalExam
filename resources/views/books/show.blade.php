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
                <button type="button"
                        onclick="openDeleteModal()"
                        class="px-3.5 py-2 text-[13px] font-semibold bg-[#B44D3B] text-white rounded-md hover:bg-[#9E3F2F] transition-colors cursor-pointer inline-flex items-center gap-1.5">
                    <x-bi-trash class="w-3.5 h-3.5" />
                    <span>Delete</span>
                </button>
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

<!-- Delete Confirmation Dialog -->
<dialog id="delete-modal" class="fixed inset-0 m-auto p-0 rounded-lg max-w-[420px] w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428]">
    <div class="p-6">
        <h2 class="text-section text-[#B44D3B] mb-2">Delete Book Entry</h2>
        <p class="text-[14px] text-[#6B5D45] mb-5">
            Are you sure you want to delete <span class="font-semibold text-[#3D3428] italic">"{{ $book->title }}"</span> from the ledger? This action cannot be reversed.
        </p>

        <form action="{{ route('books.destroy', $book) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end gap-3">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2.5 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        class="bg-[#B44D3B] text-white font-semibold text-[13px] py-2.5 px-4 rounded-md hover:bg-[#9E3F2F] transition-colors cursor-pointer">
                    Delete
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openDeleteModal() {
        document.getElementById('delete-modal').showModal();
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').close();
    }
</script>
@endsection
