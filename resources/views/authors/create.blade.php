@extends('layouts.app', ['title' => 'Create Author'])

@section('content')
<div class="space-y-6 max-w-xl">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-[13px] text-[#6B5D45]">
        <a href="{{ route('authors.index') }}" class="hover:text-[#3D3428] inline-flex items-center gap-1 transition-colors">
            <x-bi-arrow-left class="w-4 h-4" />
            <span>Back to Authors</span>
        </a>
        <span>/</span>
        <span class="text-[#3D3428] font-medium">New Author</span>
    </div>

    <!-- Form Card -->
    <div class="bg-[#FFFDF7] border border-[#D4C5A9] rounded-lg p-6 sm:p-8">
        <h1 class="text-section mb-6">Create New Author</h1>

        <form action="{{ route('authors.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-label-ledger mb-1.5">Author Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           required
                           maxlength="255"
                           placeholder="e.g. Virginia Woolf"
                           class="w-full bg-[#FFFDF7] border @error('name') border-[#B44D3B] @else border-[#D4C5A9] @enderror text-[#3D3428] placeholder-[#9C8E76] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
                    @error('name')
                        <p class="text-[12px] text-[#B44D3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Birth Date Field -->
                <div>
                    <label for="birth_date" class="block text-label-ledger mb-1.5">Birth Date</label>
                    <input type="date"
                           name="birth_date"
                           id="birth_date"
                           value="{{ old('birth_date') }}"
                           required
                           class="w-full bg-[#FFFDF7] border @error('birth_date') border-[#B44D3B] @else border-[#D4C5A9] @enderror text-[#3D3428] rounded-md px-3 py-2.5 text-[14px] focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C]/25" />
                    @error('birth_date')
                        <p class="text-[12px] text-[#B44D3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-[#D4C5A9]">
                <a href="{{ route('authors.index') }}"
                   class="px-4 py-2.5 text-[13px] font-semibold border border-[#B8A88A] text-[#3D3428] rounded-md hover:bg-[#F5EDD6] transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-[#C9A84C] text-[#3D3428] font-semibold text-[13px] py-2.5 px-4 rounded-md hover:bg-[#B8973E] transition-colors cursor-pointer">
                    Save Author
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
