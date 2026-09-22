@extends('layouts.app', ['title' => 'Book & Author Management'])

@section('content')
<div class="space-y-6">
    <!-- Top Action Row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-page-title">Books</h1>
            <p class="text-caption-ledger mt-1">Catalog entries and publications</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button"
                    onclick="openCreateBookModal()"
                    class="bg-[#C9A84C] text-[#3D3428] font-semibold text-[13px] py-2.5 px-4 rounded-md hover:bg-[#B8973E] transition-colors inline-flex items-center gap-2 cursor-pointer shadow-none">
                <x-bi-plus-lg class="w-4 h-4" />
                <span>Add New Book</span>
            </button>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form method="GET" action="{{ route('books.index') }}" class="flex items-center gap-2 max-w-md w-full">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9C8E76]">
                    <x-bi-search class="w-4 h-4" />
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search books by title..."
                       class="w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428] placeholder-[#9C8E76] rounded-md pl-9 pr-3 py-2 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
            </div>
            <button type="submit"
                    class="px-3.5 py-2 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors cursor-pointer">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('books.index') }}"
                   class="text-[13px] text-[#6B5D45] hover:text-[#3D3428] px-2 py-1 underline underline-offset-2">
                    Clear
                </a>
            @endif
        </form>

        <div class="text-[12px] text-[#6B5D45]">
            Showing {{ $books->total() }} total {{ Str::plural('book', $books->total()) }}
        </div>
    </div>

    <!-- Data Table Container (Warm-Paper Ledger) -->
    <div class="bg-[#FFFDF7] rounded-none overflow-x-auto">
        <table class="w-full text-left border-collapse" id="books-table">
            <thead>
                <tr class="border-b border-[#B8A88A]">
                    <th scope="col" class="py-3 px-4 text-column-ledger">Title</th>
                    <th scope="col" class="py-3 px-4 text-column-ledger">Author</th>
                    <th scope="col" class="py-3 px-4 text-column-ledger">Published Date</th>
                    <th scope="col" class="py-3 px-4 text-column-ledger text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#D4C5A9]">
                @forelse ($books as $book)
                    <tr id="book-row-{{ $book->id }}"
                        class="text-[15px] hover:bg-[#C9A84C]/10 transition-colors group"
                        data-id="{{ $book->id }}"
                        data-title="{{ $book->title }}"
                        data-author-id="{{ $book->author_id }}"
                        data-author-name="{{ $book->author?->name }}"
                        data-published-date="{{ $book->published_date?->format('Y-m-d') }}">
                        <td class="py-3.5 px-4 font-normal text-[#3D3428]">
                            <a href="{{ route('books.show', $book) }}" class="italic text-[#3D3428] hover:text-[#C9A84C] transition-colors">
                                {{ $book->title }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 text-[#3D3428]">
                            @if($book->author)
                                <a href="{{ route('authors.show', $book->author) }}" class="hover:underline text-[#3D3428] font-medium transition-colors">
                                    {{ $book->author->name }}
                                </a>
                            @else
                                <span class="text-[#9C8E76] italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-[13px] text-[#6B5D45]">
                            {{ $book->published_date?->format('M d, Y') ?? 'N/A' }}
                        </td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap text-[13px] space-x-3">
                            <a href="{{ route('books.show', $book) }}"
                               class="text-[#6B5D45] hover:text-[#3D3428] font-medium transition-colors">
                                View
                            </a>
                            <button type="button"
                                    onclick="openEditBookModal(this)"
                                    class="text-[#6B5D45] hover:text-[#3D3428] font-medium transition-colors cursor-pointer">
                                Edit
                            </button>
                            <button type="button"
                                    onclick="openDeleteBookModal({{ $book->id }}, '{{ addslashes($book->title) }}')"
                                    class="text-[#B44D3B] hover:text-[#9E3F2F] font-medium transition-colors cursor-pointer">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 px-4 text-center text-[#9C8E76]">
                            <x-bi-book class="w-8 h-8 mx-auto mb-2 text-[#D4C5A9]" />
                            <p class="text-[14px]">No books found in ledger.</p>
                            @if(request('search'))
                                <a href="{{ route('books.index') }}" class="text-[13px] text-[#C9A84C] hover:underline mt-1 inline-block">Clear search filter</a>
                            @else
                                <button type="button" onclick="openCreateBookModal()" class="text-[13px] text-[#C9A84C] hover:underline mt-1 inline-block cursor-pointer">
                                    Record your first book entry
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $books->links('vendor.pagination.ledger') }}
    </div>
</div>

<!-- Book Create / Edit Modal Dialog -->
<dialog id="book-modal" class="fixed inset-0 m-auto p-0 rounded-lg max-w-[480px] w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428]">
    <div class="p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 id="book-modal-title" class="text-section">Add New Book</h2>
            <button type="button" onclick="closeBookModal()" class="text-[#6B5D45] hover:text-[#3D3428] p-1 cursor-pointer">
                <x-bi-x-lg class="w-5 h-5" />
            </button>
        </div>

        <form id="book-modal-form" method="POST" onsubmit="handleBookFormSubmit(event)">
            @csrf
            <input type="hidden" name="_method" id="book-form-method" value="POST">
            <input type="hidden" id="book-id" value="">

            <div class="space-y-4">
                <!-- Title Field -->
                <div>
                    <label for="modal-book-title" class="block text-label-ledger mb-1.5">Book Title</label>
                    <input type="text"
                           name="title"
                           id="modal-book-title"
                           required
                           maxlength="255"
                           placeholder="e.g. The Count of Monte Cristo"
                           class="w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428] placeholder-[#9C8E76] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
                    <p class="field-error text-[12px] text-[#B44D3B] mt-1 hidden" data-error-for="title"></p>
                </div>

                <!-- Author Field -->
                <div>
                    <label for="modal-book-author-id" class="block text-label-ledger mb-1.5">Author</label>
                    <select name="author_id"
                            id="modal-book-author-id"
                            required
                            class="w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25">
                        <option value="">-- Select an Author --</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                        @endforeach
                    </select>
                    <p class="field-error text-[12px] text-[#B44D3B] mt-1 hidden" data-error-for="author_id"></p>
                </div>

                <!-- Published Date Field -->
                <div>
                    <label for="modal-book-published-date" class="block text-label-ledger mb-1.5">Published Date</label>
                    <input type="date"
                           name="published_date"
                           id="modal-book-published-date"
                           required
                           class="w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
                    <p class="field-error text-[12px] text-[#B44D3B] mt-1 hidden" data-error-for="published_date"></p>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-[#D4C5A9]">
                <button type="button"
                        onclick="closeBookModal()"
                        class="px-4 py-2.5 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        id="book-submit-button"
                        class="bg-[#C9A84C] text-[#3D3428] font-semibold text-[13px] py-2.5 px-4 rounded-md hover:bg-[#B8973E] transition-colors cursor-pointer">
                    Save Book
                </button>
            </div>
        </form>
    </div>
</dialog>

<!-- Delete Confirmation Modal Dialog -->
<dialog id="delete-modal" class="fixed inset-0 m-auto p-0 rounded-lg max-w-[420px] w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428]">
    <div class="p-6">
        <h2 class="text-section text-[#B44D3B] mb-2">Delete Book Entry</h2>
        <p class="text-[14px] text-[#6B5D45] mb-5">
            Are you sure you want to delete <span id="delete-item-name" class="font-semibold text-[#3D3428] italic"></span> from the ledger? This action cannot be reversed.
        </p>

        <form id="delete-book-form" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end gap-3">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2.5 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        id="confirm-delete-button"
                        class="bg-[#B44D3B] text-white font-semibold text-[13px] py-2.5 px-4 rounded-md hover:bg-[#9E3F2F] transition-colors cursor-pointer">
                    Delete
                </button>
            </div>
        </form>
    </div>
</dialog>

<!-- Script for Book Modals & AJAX CRUD -->
<script>
    let activeDeleteId = null;

    function clearBookErrors() {
        document.querySelectorAll('#book-modal .field-error').forEach(p => {
            p.textContent = '';
            p.classList.add('hidden');
        });
        document.querySelectorAll('#book-modal input, #book-modal select').forEach(input => {
            input.classList.remove('border-[#B44D3B]');
        });
    }

    function openCreateBookModal() {
        clearBookErrors();
        document.getElementById('book-modal-title').textContent = 'Add New Book';
        document.getElementById('book-modal-form').reset();
        document.getElementById('book-form-method').value = 'POST';
        document.getElementById('book-id').value = '';
        document.getElementById('book-modal-form').action = "{{ route('books.store') }}";
        document.getElementById('book-submit-button').textContent = 'Save Book';
        document.getElementById('book-modal').showModal();
    }

    function openEditBookModal(btn) {
        clearBookErrors();
        const row = btn.closest('tr');
        const id = row.dataset.id;
        const title = row.dataset.title;
        const authorId = row.dataset.authorId;
        const publishedDate = row.dataset.publishedDate;

        document.getElementById('book-modal-title').textContent = 'Edit Book';
        document.getElementById('book-id').value = id;
        document.getElementById('book-form-method').value = 'PUT';
        document.getElementById('modal-book-title').value = title || '';
        document.getElementById('modal-book-author-id').value = authorId || '';
        document.getElementById('modal-book-published-date').value = publishedDate || '';
        document.getElementById('book-modal-form').action = `/books/${id}`;
        document.getElementById('book-submit-button').textContent = 'Update Book';
        document.getElementById('book-modal').showModal();
    }

    function closeBookModal() {
        document.getElementById('book-modal').close();
    }

    function openDeleteBookModal(id, title) {
        document.getElementById('delete-item-name').textContent = `"${title}"`;
        document.getElementById('delete-book-form').action = `/books/${id}`;
        document.getElementById('delete-modal').showModal();
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').close();
    }

    async function handleBookFormSubmit(event) {
        event.preventDefault();
        clearBookErrors();

        const form = event.target;
        const bookId = document.getElementById('book-id').value;
        const isEdit = Boolean(bookId);
        const submitBtn = document.getElementById('book-submit-button');

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

        const formData = new FormData(form);
        const url = isEdit ? `/books/${bookId}` : "{{ route('books.store') }}";

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    for (const [field, messages] of Object.entries(data.errors)) {
                        const errorP = document.querySelector(`.field-error[data-error-for="${field}"]`);
                        const inputEl = document.querySelector(`[name="${field}"]`);
                        if (errorP) {
                            errorP.textContent = messages[0];
                            errorP.classList.remove('hidden');
                        }
                        if (inputEl) {
                            inputEl.classList.add('border-[#B44D3B]');
                        }
                    }
                } else {
                    showFlash(data.message || 'An error occurred while saving the book.', 'error');
                }
                return;
            }

            closeBookModal();
            showFlash(data.message || (isEdit ? 'Book updated successfully.' : 'Book created successfully.'), 'success');

            // Seamless page reload to re-render paginated ledger
            setTimeout(() => {
                window.location.reload();
            }, 600);

        } catch (err) {
            console.error(err);
            showFlash('Network error. Please try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }

    function showFlash(message, type = 'success') {
        const container = document.getElementById('flash-container');
        if (!container) return;

        const isSuccess = type === 'success';
        const bg = isSuccess ? 'bg-[rgba(90,125,76,0.12)]' : 'bg-[rgba(180,77,59,0.12)]';
        const text = isSuccess ? 'text-[#5A7D4C]' : 'text-[#B44D3B]';
        const border = isSuccess ? 'border-[#5A7D4C]/30' : 'border-[#B44D3B]/30';

        const successIcon = `<x-bi-check-circle class="w-5 h-5 flex-shrink-0" />`;
        const errorIcon = `<x-bi-exclamation-circle class="w-5 h-5 flex-shrink-0" />`;
        const closeIcon = `<x-bi-x class="w-4 h-4" />`;

        const alert = document.createElement('div');
        alert.className = `flash-alert ${bg} ${text} border ${border} rounded-md py-3 px-4 flex items-center justify-between text-[14px]`;
        alert.innerHTML = `
            <div class="flex items-center gap-2.5">
                ${isSuccess ? successIcon : errorIcon}
                <span>${message}</span>
            </div>
            <button type="button" onclick="this.closest('.flash-alert').remove()" class="hover:opacity-75 p-1 cursor-pointer">
                ${closeIcon}
            </button>
        `;

        container.appendChild(alert);

        setTimeout(() => {
            alert.style.transition = 'opacity 300ms ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
</script>
@endsection
