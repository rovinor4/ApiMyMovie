<?php

namespace App\Models;

use App\Models\Film;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriFilm extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    public function Film(): HasMany
    {
        return $this->hasMany(Film::class);
    }
}
