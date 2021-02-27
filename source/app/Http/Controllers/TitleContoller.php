<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;


class TitleContoller extends Controller
{
    public function index()
    {
        $participants = Participant::all();
        return view('titles', ['participants' => $participants]);
    }
}
