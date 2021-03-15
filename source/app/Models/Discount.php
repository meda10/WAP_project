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
        $discount = Discount::with('reservations')->where(DB::raw('BINARY `code`'), $code)->first();
        if (count($discount->reservations) == 0)
            return ['percent' => $discount['percent']];
        return [];
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'discount_reservation', 'discount_id', 'reservation_id');
    }

    public static function applyDiscountOnReservation($discountCode, $reservationId)
    {
        $discount = Discount::where('code', $discountCode)->first();
        $discount->reservations()->attach($reservationId);
    }
}
