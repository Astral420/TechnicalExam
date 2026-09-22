<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Author;
use Illuminate\Support\Facades\DB;

final readonly class UpdateAuthorAction
{
    /**
     * Execute the action.
     */
    public function handle(Author $author, array $data): Author
    {
        return DB::transaction(function () use ($author, $data): Author {
            $author->update($data);

            return $author->refresh();
        });
    }
}
