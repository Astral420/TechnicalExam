<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

final readonly class DeleteBookAction
{
    /**
     * Execute the action.
     */
    public function handle(Book $book): void
    {
        DB::transaction(function () use ($book): void {
            $book->delete();
        });
    }
}
