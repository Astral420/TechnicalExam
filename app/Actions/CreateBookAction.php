<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

final readonly class CreateBookAction
{
    /**
     * Execute the action.
     */
    public function handle(array $data): Book
    {
        return DB::transaction(function () use ($data): Book {
            return Book::create($data);
        });
    }
}
