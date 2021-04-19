<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use Illuminate\Http\Request;
use App\Models\Discount;


class DiscountsController extends Controller
{
    public function checkDiscountCode(Request $request)
    {
        if ($response = Discount::checkCodeExistNotUsed($request->code))
            return $response;
        abort(400);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DiscountResource::collection(Discount::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->discount_validator();
        for ($i = 0; $i < $request['pocet']; $i++) {
            $code = AppHelper::generateDiscountCode();
            Discount::create(['code' => $code, 'percent' => $request['procenta']]);
        }
        return response()->json(['ok'=> 'ok'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return new StoresResource(Store::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return response()->json(['ok'=> 'ok'], 200);
    }

    protected function discount_validator(){
        return request()->validate([
            'pocet' => 'required|numeric|min:1',
            'procenta' => 'required|numeric|min:1|max:100',
        ]);
    }
}
