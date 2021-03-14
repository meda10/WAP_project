<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;


class DiscountsController extends Controller
{
    public function checkDiscountCode(Request $request)
    {
        if ($response = Discount::checkCodeExistNotUsed($request->code))
            return $response;
        abort(404);
    }
}
