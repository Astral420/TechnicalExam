<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Author;
use Illuminate\Support\Facades\DB;

final readonly class DeleteAuthorAction
{
    /**
     * Execute the action.
     */
    public function handle(Author $author): void
    {
        DB::transaction(function () use ($author): void {
            $author->delete();
        });
    }
}
