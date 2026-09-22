<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
