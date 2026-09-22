<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

final readonly class UpdateBookAction
{
    /**
     * Execute the action.
     */
    public function handle(Book $book, array $data): Book
    {
        return DB::transaction(function () use ($book, $data): Book {
            $book->update($data);

            return $book->refresh();
        });
    }
}
