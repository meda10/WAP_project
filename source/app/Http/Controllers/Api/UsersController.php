<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersResource;
use App\Http\Resources\UserUpdateResource;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function updateName(Request $request)
    {
        // TODO change confirmation of user to false
        return response()->json(['ok' => 'ok'], 200);
    }

    public function updateSurname(Request $request)
    {
        // TODO change confirmation of user to false
        return response()->json(['ok' => 'ok'], 200);
    }

    public function updatePassword(Request $request)
    {
        // TODO change confirmation of user to false
        return response()->json(['ok' => 'ok'], 200);
    }

    public function updateAddress(Request $request)
    {
        // TODO change confirmation of user to false
        return response()->json(['ok' => 'ok'], 200);
    }


    public function confirm_user($id){
        $user = User::findOrFail($id);
        $user['confirmed'] = 1;
        $user->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UsersResource::collection(User::all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new UserUpdateResource(User::findOrFail($request['id']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->user_validator();
        $user->update([
            'name' => $request['jmeno'],
            'surname' => $request['prijmeni'],
            'email' => $request['email'],
            'role' => $request['role'],
            'address' => $request['adresa'],
            'city' => $request['mesto'],
            'zip_code' => $request['psc'],
            'confirmed' => $request['potvrzeni'],
            'store_id' => $request['obchod'],
        ]);
        $user->save();
        //todo response
        return response()->json($user, 200);
    }

    protected function user_validator(){
        return request()->validate([
            'jmeno' => 'required|string|max:255',
            'prijmeni' => 'required|string|max:255',
            'email' => 'required|string|email|max:255', //todo |unique:users
            'role' => 'required|string|in:director,manager,employee,customer',
            'adresa' => 'required|string|max:255',
            'mesto' => 'required|string|max:255',
            'psc' => 'required|numeric', //todo number of digits
            'potvrzeni' => 'required|boolean',
            'obchod' => 'required|numeric',
        ]);
    }
}
