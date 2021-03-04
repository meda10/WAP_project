<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function getGenresMenu() 
    {
        return Genre::select(['genre_name AS name'])->orderBy('genre_name', 'ASC')->take(5)->get();
    }
}
