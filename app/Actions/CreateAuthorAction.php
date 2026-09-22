<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Author;
use Illuminate\Support\Facades\DB;

final readonly class CreateAuthorAction
{
    /**
     * Execute the action.
     */
    public function handle(array $data): Author
    {
        return DB::transaction(function () use ($data): Author {
            return Author::create($data);
        });
    }
}
