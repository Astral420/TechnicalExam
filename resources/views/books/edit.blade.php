@extends('layouts.app', ['title' => 'Edit ' . $book->title])

@section('content')
<div class="space-y-6 max-w-xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-[13px] text-[#6B5D45]">
        <a href="{{ route('books.index') }}" class="hover:text-[#3D3428] inline-flex items-center gap-1 transition-colors">
            <x-bi-arrow-left class="w-4 h-4" />
            <span>Back to Books</span>
        </a>
        <span>/</span>
        <span class="text-[#3D3428] font-medium truncate max-w-xs">Edit: {{ $book->title }}</span>
    </div>

    <!-- Form Card -->
    <div class="bg-[#FFFDF7] border border-[#D4C5A9] rounded-lg p-6 sm:p-8">
        <h1 class="text-section mb-6">Edit Book Entry</h1>

        <form action="{{ route('books.update', $book) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Title Field -->
                <div>
                    <label for="title" class="block text-label-ledger mb-1.5">Book Title</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $book->title) }}"
                           required
                           maxlength="255"
                           class="w-full bg-[#FFFDF7] border @error('title') border-[#B44D3B] @else border-[#D4C5A9] @enderror text-[#3D3428] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
                    @error('title')
                        <p class="text-[12px] text-[#B44D3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Author Field -->
                <div>
                    <label for="author_id" class="block text-label-ledger mb-1.5">Author</label>
                    <select name="author_id"
                            id="author_id"
                            required
                            class="w-full bg-[#FFFDF7] border @error('author_id') border-[#B44D3B] @else border-[#D4C5A9] @enderror text-[#3D3428] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25">
                        <option value="">Select an Author</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" @selected(old('author_id', $book->author_id) == $author->id)>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('author_id')
                        <p class="text-[12px] text-[#B44D3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published Date Field -->
                <div>
                    <label for="published_date" class="block text-label-ledger mb-1.5">Published Date</label>
                    <input type="date"
                           name="published_date"
                           id="published_date"
                           value="{{ old('published_date', $book->published_date?->format('Y-m-d')) }}"
                           required
                           class="w-full bg-[#FFFDF7] border @error('published_date') border-[#B44D3B] @else border-[#D4C5A9] @enderror text-[#3D3428] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
                    @error('published_date')
                        <p class="text-[12px] text-[#B44D3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-[#D4C5A9]">
                <a href="{{ route('books.index') }}"
                   class="px-4 py-2.5 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-[#C9A84C] text-[#3D3428] font-semibold text-[13px] py-2.5 px-4 rounded-md hover:bg-[#B8973E] transition-colors cursor-pointer">
                    Update Book
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
