<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['code', 'percent'];

    public static function checkCodeNotExist($code)
    {
        return Discount::where('code', $code)->first() ? true : false;
    }

    public static function checkCodeExistNotUsed($code)
    {
        $discount = Discount::with('reservations')->where(DB::raw('BINARY `code`'), $code)->first();
        if ($discount->reservations == null)
            return ['percent' => $discount['percent']];
        return [];
    }

    public function reservations()
    {
        return $this->hasOne(Reservation::class, 'discount_id');
    }
}
