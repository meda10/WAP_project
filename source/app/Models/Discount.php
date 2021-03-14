<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function checkCodeNotExist($code)
    {
        return Discount::where('code', $code)->first() ? true : false;
    }

    public static function checkCodeExistNotUsed($code)
    {
        return Discount::select(['percent'])->where(DB::raw('BINARY `code`'), $code)->where('reservation_id', null)->first();
    }
}
