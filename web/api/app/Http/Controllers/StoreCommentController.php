<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

class StoreCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($store)
    {
        $stores = \App\Models\StoreComment::where('idStore', '=', $store)
                                          ->orderBy('id', 'desc')
                                          ->join('account', 'account.id', '=', 'store_comment.idAccount')
                                          ->get(array('store_comment.*', 'account.name', 'account.lastname', 'account.image', 'account.facebookID'));

        return response()->json($stores);
    }

    public function shows($store)
    {
        $stores = \App\Models\StoreComment::where('idShows', '=', $store)
                                          ->orderBy('id', 'desc')
                                          ->join('account', 'account.id', '=', 'store_comment.idAccount')
                                          ->get(array('store_comment.*', 'account.name', 'account.lastname', 'account.image', 'account.facebookID'));

        return response()->json($stores);
    }

    public function store(Request $request)
    {
        $user = new \App\Models\StoreComment();
        $user->idAccount = $request->idAccount;
        if ($request->idStore) {
          $user->idStore = $request->idStore;
        } else {
          $user->idShows = $request->idShows;
        }
        $user->message = $request->message;
        $user->date = $request->date;
        $user->save();

        return response()->json(["id" => $user->id]);
    }
}
