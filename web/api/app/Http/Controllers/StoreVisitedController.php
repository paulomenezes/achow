<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

class StoreVisitedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($user, $type, $store)
    {
        $stores = \App\Models\StoreVisited::where("idVisitedType", '=', $type)->where('idStore', '=', $store)->count();
        $users = \App\Models\StoreVisited::where("idVisitedType", '=', $type)->where('idStore', '=', $store)->where('idAccount', '=', $user)->count();

        return response()->json([
            'visited' => $stores,
            'user' => $users
        ]);
    }

    public function shows($user, $type, $store)
    {
        $stores = \App\Models\StoreVisited::where("idVisitedType", '=', $type)->where('idShows', '=', $store)->count();
        $users = \App\Models\StoreVisited::where("idVisitedType", '=', $type)->where('idShows', '=', $store)->where('idAccount', '=', $user)->count();

        $accounts = \App\Models\StoreVisited::where("idVisitedType", '=', $type)
                                            ->where('idShows', '=', $store)
                                            ->join('account', 'account.id', '=', 'store_visited.idAccount')
                                            ->get(array('account.*'));

        return response()->json([
            'visited' => $stores,
            'user' => $users,
            'accounts' => $accounts
        ]);
    }

    public function favorite($user)
    {
        $users = \App\Models\Store::join('store_visited', 'store_visited.idStore', '=', 'store.id')
                                  ->where('store_visited.idVisitedType', '=', '5')
                                  ->where('store_visited.idAccount', '=', $user)
                                  ->orderBy('store.name', 'asc')
                                  ->get(array('store.*'));

        return response()->json($users->toArray());
    }

    public function store(Request $request)
    {
        if ($request->idStore) {
            $visited = \App\Models\StoreVisited::where('idAccount', '=',  $request->idAccount)
                                               ->where('idStore', '=', $request->idStore)
                                               ->where('idVisitedType', '=', $request->idVisitedType)
                                               ->get();
            if (sizeof($visited) == 0) {
                $user = new \App\Models\StoreVisited();
                $user->idAccount = $request->idAccount;
                $user->idStore = $request->idStore;
                $user->idVisitedType = $request->idVisitedType;
                $user->date = $request->date;
                $user->save();

                return response()->json(['id' => $user->id]);
            } else {
                if ($request->idVisitedType == 5) {
                    $visited[0]->delete();
                    return response()->json(['removed' => 'removed']);
                } else {
                    return response()->json(['failed' => 'failed']);
                }
            }
        } else {
            $visited = \App\Models\StoreVisited::where('idAccount', '=',  $request->idAccount)
                                               ->where('idShows', '=', $request->idShows)
                                               ->where('idVisitedType', '=', $request->idVisitedType)
                                               ->get();
            if (sizeof($visited) == 0) {
                $user = new \App\Models\StoreVisited();
                $user->idAccount = $request->idAccount;
                $user->idShows = $request->idShows;
                $user->idVisitedType = $request->idVisitedType;
                $user->date = $request->date;
                $user->save();

                return response()->json(['id' => $user->id]);
            } else {
                return response()->json(['failed' => 'failed']);
            }
        }
    }
}
