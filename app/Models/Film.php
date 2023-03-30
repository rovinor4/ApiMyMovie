<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Film extends Model
{
    use HasFactory;
    protected $guarded = [
        "id"
    ];
    public function kategori()
    {
        return $this->hasOne(KategoriFilm::class, "id", "kategori_film_id");
    }

    public function comments(): HasMany
    {
        return $this->hasMany(FilmCommentar::class);
    }
}
