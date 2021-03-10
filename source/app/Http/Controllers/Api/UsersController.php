<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
