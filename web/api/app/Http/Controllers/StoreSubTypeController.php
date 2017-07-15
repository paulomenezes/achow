<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

class StoreSubTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = \App\Models\StoreSubType::orderBy('Store_Type_id', 'asc')->orderBy('name', 'asc')->get();
        for ($i=0; $i < sizeof($users); $i++) { 
            //$users[$i]->name = utf8_decode($users[$i]->name);
        }
        return response()->json($users->toArray());
    }
}
