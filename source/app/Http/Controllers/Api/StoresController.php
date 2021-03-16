<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

class StoresController extends Controller
{
    public function getStores()
    {
        return Store::getStores();
    }
}
