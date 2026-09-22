@extends('layouts.app', ['title' => 'Authors'])

@section('content')
<div class="space-y-6">
    <!-- Top Action Row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-page-title">Authors</h1>
            <p class="text-caption-ledger mt-1">Registered writers and catalog contributors</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button"
                    onclick="openCreateAuthorModal()"
                    class="bg-[#C9A84C] text-[#3D3428] font-semibold text-[13px] py-2.5 px-4 rounded-md hover:bg-[#B8973E] transition-colors inline-flex items-center gap-2 cursor-pointer shadow-none">
                <x-bi-plus-lg class="w-4 h-4" />
                <span>Add New Author</span>
            </button>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form method="GET" action="{{ route('authors.index') }}" class="flex items-center gap-2 max-w-md w-full">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9C8E76]">
                    <x-bi-search class="w-4 h-4" />
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search authors by name..."
                       class="w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428] placeholder-[#9C8E76] rounded-md pl-9 pr-3 py-2 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
            </div>
            <button type="submit"
                    class="px-3.5 py-2 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors cursor-pointer">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('authors.index') }}"
                   class="text-[13px] text-[#6B5D45] hover:text-[#3D3428] px-2 py-1 underline underline-offset-2">
                    Clear
                </a>
            @endif
        </form>

        <div id="ledger-total-count" class="text-[12px] text-[#6B5D45]">
            Showing {{ $authors->total() }} total {{ Str::plural('author', $authors->total()) }}
        </div>
    </div>

    <!-- Data Table Container (Warm-Paper Ledger) -->
    <div id="ledger-table-container" class="space-y-4">
        <div class="bg-[#FFFDF7] rounded-none overflow-x-auto">
            <table class="w-full text-left border-collapse" id="authors-table">
            <thead>
                <tr class="border-b border-[#B8A88A]">
                    <th scope="col" class="py-3 px-4 text-column-ledger">Name</th>
                    <th scope="col" class="py-3 px-4 text-column-ledger">Birth Date</th>
                    <th scope="col" class="py-3 px-4 text-column-ledger">Books</th>
                    <th scope="col" class="py-3 px-4 text-column-ledger text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#D4C5A9]">
                @forelse ($authors as $author)
                    <tr id="author-row-{{ $author->id }}"
                        class="text-[15px] hover:bg-[#C9A84C]/10 transition-colors group"
                        data-id="{{ $author->id }}"
                        data-name="{{ $author->name }}"
                        data-birth-date="{{ $author->birth_date?->format('Y-m-d') }}">
                        <td class="py-3.5 px-4 font-normal text-[#3D3428]">
                            <a href="{{ route('authors.show', $author) }}" class="text-[#3D3428] font-semibold hover:text-[#C9A84C] transition-colors">
                                {{ $author->name }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 text-[13px] text-[#6B5D45]">
                            {{ $author->birth_date?->format('M d, Y') ?? 'N/A' }}
                        </td>
                        <td class="py-3.5 px-4 text-[13px] text-[#3D3428]">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[12px] font-medium bg-[#F5EDD6] text-[#6B5D45] border border-[#D4C5A9]">
                                {{ $author->books_count }} {{ Str::plural('book', $author->books_count) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap text-[13px] space-x-3">
                            <a href="{{ route('authors.show', $author) }}"
                               class="text-[#6B5D45] hover:text-[#3D3428] font-medium transition-colors">
                                View
                            </a>
                            <button type="button"
                                    onclick="openEditAuthorModal(this)"
                                    class="text-[#6B5D45] hover:text-[#3D3428] font-medium transition-colors cursor-pointer">
                                Edit
                            </button>
                            <button type="button"
                                    onclick="openDeleteAuthorModal({{ $author->id }}, '{{ addslashes($author->name) }}')"
                                    class="text-[#B44D3B] hover:text-[#9E3F2F] font-medium transition-colors cursor-pointer">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 px-4 text-center text-[#9C8E76]">
                            <x-bi-person class="w-8 h-8 mx-auto mb-2 text-[#D4C5A9]" />
                            <p class="text-[14px]">No authors found in ledger.</p>
                            @if(request('search'))
                                <a href="{{ route('authors.index') }}" class="text-[13px] text-[#C9A84C] hover:underline mt-1 inline-block">Clear search filter</a>
                            @else
                                <button type="button" onclick="openCreateAuthorModal()" class="text-[13px] text-[#C9A84C] hover:underline mt-1 inline-block cursor-pointer">
                                    Record your first author entry
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
        {{ $authors->links('vendor.pagination.ledger') }}
    </div>
    </div>
</div>

<!-- Author Create / Edit Modal Dialog -->
<dialog id="author-modal" class="fixed inset-0 m-auto p-0 rounded-lg max-w-[480px] w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428]">
    <div class="p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 id="author-modal-title" class="text-section">Add New Author</h2>
            <button type="button" onclick="closeAuthorModal()" class="text-[#6B5D45] hover:text-[#3D3428] p-1 cursor-pointer">
                <x-bi-x-lg class="w-5 h-5" />
            </button>
        </div>

        <form id="author-modal-form" method="POST" onsubmit="handleAuthorFormSubmit(event)">
            @csrf
            <input type="hidden" name="_method" id="author-form-method" value="POST">
            <input type="hidden" id="author-id" value="">

            <div class="space-y-4">
                <!-- Name Field -->
                <div>
                    <label for="modal-author-name" class="block text-label-ledger mb-1.5">Author Name</label>
                    <input type="text"
                           name="name"
                           id="modal-author-name"
                           required
                           maxlength="255"
                           placeholder="e.g. Gabriel García Márquez"
                           class="w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428] placeholder-[#9C8E76] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
                    <p class="field-error text-[12px] text-[#B44D3B] mt-1 hidden" data-error-for="name"></p>
                </div>

                <!-- Birth Date Field -->
                <div>
                    <label for="modal-author-birth-date" class="block text-label-ledger mb-1.5">Birth Date</label>
                    <input type="date"
                           name="birth_date"
                           id="modal-author-birth-date"
                           required
                           class="w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
                    <p class="field-error text-[12px] text-[#B44D3B] mt-1 hidden" data-error-for="birth_date"></p>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-[#D4C5A9]">
                <button type="button"
                        onclick="closeAuthorModal()"
                        class="px-4 py-2.5 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        id="author-submit-button"
                        class="bg-[#C9A84C] text-[#3D3428] font-semibold text-[13px] py-2.5 px-4 rounded-md hover:bg-[#B8973E] transition-colors cursor-pointer">
                    Save Author
                </button>
            </div>
        </form>
    </div>
</dialog>

<!-- Delete Confirmation Modal Dialog -->
<dialog id="delete-modal" class="fixed inset-0 m-auto p-0 rounded-lg max-w-[420px] w-full bg-[#FFFDF7] border border-[#D4C5A9] text-[#3D3428]">
    <div class="p-6">
        <h2 class="text-section text-[#B44D3B] mb-2">Delete Author Entry</h2>
        <p class="text-[14px] text-[#6B5D45] mb-5">
            Are you sure you want to delete <span id="delete-item-name" class="font-semibold text-[#3D3428]"></span>? Deleting this author will also remove all their associated books from the ledger.
        </p>

        <form id="delete-author-form" method="POST" action="" onsubmit="handleDeleteAuthorSubmit(event)">
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

<!-- Script for Author Modals & AJAX CRUD -->
<script>
    let activeDeleteId = null;

    function clearAuthorErrors() {
        document.querySelectorAll('#author-modal .field-error').forEach(p => {
            p.textContent = '';
            p.classList.add('hidden');
        });
        document.querySelectorAll('#author-modal input').forEach(input => {
            input.classList.remove('border-[#B44D3B]');
        });
    }

    function openCreateAuthorModal() {
        clearAuthorErrors();
        document.getElementById('author-modal-title').textContent = 'Add New Author';
        document.getElementById('author-modal-form').reset();
        document.getElementById('author-form-method').value = 'POST';
        document.getElementById('author-id').value = '';
        document.getElementById('author-modal-form').action = "{{ route('authors.store') }}";
        document.getElementById('author-submit-button').textContent = 'Save Author';
        document.getElementById('author-modal').showModal();
    }

    function openEditAuthorModal(btn) {
        clearAuthorErrors();
        const row = btn.closest('tr');
        const id = row.dataset.id;
        const name = row.dataset.name;
        const birthDate = row.dataset.birthDate;

        document.getElementById('author-modal-title').textContent = 'Edit Author';
        document.getElementById('author-id').value = id;
        document.getElementById('author-form-method').value = 'PUT';
        document.getElementById('modal-author-name').value = name || '';
        document.getElementById('modal-author-birth-date').value = birthDate || '';
        document.getElementById('author-modal-form').action = `/authors/${id}`;
        document.getElementById('author-submit-button').textContent = 'Update Author';
        document.getElementById('author-modal').showModal();
    }

    function closeAuthorModal() {
        document.getElementById('author-modal').close();
    }

    function openDeleteAuthorModal(id, name) {
        document.getElementById('delete-item-name').textContent = `"${name}"`;
        document.getElementById('delete-author-form').action = `/authors/${id}`;
        document.getElementById('delete-modal').showModal();
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').close();
    }

    async function handleDeleteAuthorSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = document.getElementById('confirm-delete-button');

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });

            const data = await response.json();

            if (!response.ok) {
                showFlash(data.message || 'An error occurred while deleting the author.', 'error');
                return;
            }

            closeDeleteModal();
            showFlash(data.message || 'Author deleted successfully.', 'success');
            await refreshLedger();

        } catch (err) {
            console.error(err);
            showFlash('Network error. Please try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }

    async function handleAuthorFormSubmit(event) {
        event.preventDefault();
        clearAuthorErrors();

        const form = event.target;
        const authorId = document.getElementById('author-id').value;
        const isEdit = Boolean(authorId);
        const submitBtn = document.getElementById('author-submit-button');

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

        const formData = new FormData(form);
        const url = isEdit ? `/authors/${authorId}` : "{{ route('authors.store') }}";

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
                    showFlash(data.message || 'An error occurred while saving the author.', 'error');
                }
                return;
            }

            closeAuthorModal();
            showFlash(data.message || (isEdit ? 'Author updated successfully.' : 'Author created successfully.'), 'success');
            await refreshLedger();

        } catch (err) {
            console.error(err);
            showFlash('Network error. Please try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }

    async function refreshLedger(url = window.location.href) {
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) return;

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTable = doc.getElementById('ledger-table-container');
            const currentTable = document.getElementById('ledger-table-container');
            if (newTable && currentTable) {
                currentTable.innerHTML = newTable.innerHTML;
            }

            const newCount = doc.getElementById('ledger-total-count');
            const currentCount = document.getElementById('ledger-total-count');
            if (newCount && currentCount) {
                currentCount.innerHTML = newCount.innerHTML;
            }
        } catch (err) {
            console.error('Error refreshing ledger table:', err);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const tableContainer = document.getElementById('ledger-table-container');
        if (tableContainer) {
            tableContainer.addEventListener('click', async (e) => {
                const link = e.target.closest('a');
                if (link && link.closest('nav[role="navigation"]')) {
                    e.preventDefault();
                    await refreshLedger(link.href);
                    window.history.pushState({}, '', link.href);
                }
            });
        }
    });

    window.addEventListener('popstate', () => {
        refreshLedger(window.location.href);
    });

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
