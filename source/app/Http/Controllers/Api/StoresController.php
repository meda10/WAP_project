<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreSelectResource;
use App\Http\Resources\StoresResource;
use Illuminate\Http\Request;
use App\Models\Store;

class StoresController extends Controller
{
    public function getStores()
    {
        return Store::getStores();
    }

    public function get_items_select(){
        return StoreSelectResource::collection(Store::all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StoresResource::collection(Store::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->store_validator();
        Store::create([
            'description' => $request['popis'],
            'address' => $request['adresa'],
            'zip_code' => $request['psc'],
            'city' => $request['mesto'],
        ]);
        //todo response
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return new StoresResource(Store::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        $this->store_validator();
        $store->update([
            'description' => $request['popis'],
            'address' => $request['adresa'],
            'zip_code' => $request['psc'],
            'city' => $request['mesto'],
        ]);
        $store->save();
        //todo response
        return response()->json($store, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();
    }

    protected function store_validator(){
        return request()->validate([
            'popis' => 'required|string|max:1000',
            'adresa' => 'required|string|max:255',
            'psc' => 'required|numeric', //todo number of digits
            'mesto' => 'required|string|max:255',
        ]);
    }
}
