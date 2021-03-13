<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function getStores()
    {
        return Store::select(['id', DB::raw("CONCAT(address, ', ', city, ', ', zip_code) AS address"), 'description'])->get();
    }
}
