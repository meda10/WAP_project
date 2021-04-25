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
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $user->update([
            'name' => $validated['name'],
        ]);
        $user->save();
        return response()->json(['ok' => 'ok', 'name' => $validated['name']], 200);
    }

    public function updateSurname(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'surname' => 'required|string|max:255',
        ]);
        $user->update([
            'surname' => $validated['surname'],
        ]);
        $user->save();
        return response()->json(['ok' => 'ok', 'surname' => $validated['surname']], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'current_password' => 'password',
            'password' => 'sometimes|required_with:password_confirmation|string|confirmed',
        ]);
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        $user->save();
        return response()->json(['ok' => 'ok'], 200);
    }

    public function updateEmail(Request $request){
        $user = Auth::user();
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        $user->update([
            'email' => $validated['email'],
        ]);
        $user->save();
        return response()->json(['ok' => 'ok', 'email' => $validated['email']], 200);
    }

    public function updateAddress(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|numeric|digits:5',
        ]);
        $user->update([
            'address' => $validated['address'],
            'city' => $validated['city'],
            'zip_code' => $validated['zip_code'],
        ]);
        $user->save();
        return response()->json(['ok' => 'ok', 'address' => $validated['address'],
            'city' => $validated['city'], 'zip_code' => $validated['zip_code']], 200);
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

        $user = User::create([
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
        $user->assignRole(Role::where('name', $request['role'])->get());
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

        if(isset($request['password'])){
            $user->update([
                'password' => Hash::make($request['password']),
            ]);
        }
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
            'password' => 'sometimes|required_with:password_confirm|string',
            'mesto' => 'required|string|max:255',
            'psc' => 'required|numeric|digits:5',
            'potvrzeni' => 'required|boolean',
            'obchod' => 'required|numeric',
        ]);
    }
}
