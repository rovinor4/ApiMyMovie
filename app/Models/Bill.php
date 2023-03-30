<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"

    ];
    public function users()
    {
        return $this->hasOne(User::class,"id","user_id");
    }
    public function films()
    {
        return $this->hasOne(Film::class,"id","film_id");
    }
}
