<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersResource;
use App\Http\Resources\UserUpdateResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

    public function get_user_info(Request $request)
    {
        $csrfToken = csrf_token();
        $jsPermissions = auth()->check()?auth()->user()->jsPermissions():'';
        return response()->json(['csrfToken' => $csrfToken, 'jsPermissions' => $jsPermissions], 200);
    }

    public function confirm_user($id){
        $user = User::findOrFail($id);
        $user['confirmed'] = 1;
        $user->save();
    }

    public function store(Request $request){
        $this->user_validator();

        User::create([
            'name' => $request['jmeno'],
            'surname' => $request['prijmeni'],
            'email' => $request['email'],
            'role' => $request['role'],
            'password' => Hash::make($request['password']),
            'address' => $request['adresa'],
            'city' => $request['mesto'],
            'zip_code' => $request['psc'],
            'store_id' => $request['obchod'],
            'confirmed' => $request['potvrzeni'],
        ]);
        return response()->json(['ok'=> 'ok'], 200);
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
        $user->removeRole($user->role);
        $user->delete();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return new UserUpdateResource(User::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->user_validator();
        $user->removeRole($user->role);
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
        $user->assignRole(Role::where('name', $request['role'])->get());
        $user->save();
        return response()->json(['ok'=> 'ok'], 200);
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
