<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = \App\Models\User::get();
        return response()->json([
                "msg" => "Success",
                "users" => $users->toArray()
            ]);
    }

    public function login(Request $request)
    {
        $users = \App\Models\User::where('email', $request->email)->where('password', $request->password)->get();
        if (sizeof($users) > 0) {
            $users[0]->gcm_regid = $request->gcmid;
            $users[0]->save();
        }
        return response()->json($users->toArray(), 200);
    }

    public function loginFacebook(Request $request)
    {
        $users = \App\Models\User::where('facebookID', $request->facebookID)->get();
        if (sizeof($users) > 0) {
            $users[0]->gcm_regid = $request->gcmid;
            $users[0]->save();
        }
        return response()->json($users->toArray(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = new \App\Models\User();
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->ocupation = $request->ocupation;
        $user->user = $request->user;
        $user->birth = $request->birth;
        $user->facebook = $request->facebook;
        $user->facebookID = $request->facebookID;
        $user->image = $request->image;
        $user->cover = $request->cover;
        $user->gcm_regid = $request->gcm_regid;
        $user->save();

        if ($request->gcm_regid) {
            $ids = array($request->gcm_regid);
            $message = array("app" => "achow");

            //$result = $this->send_notification($ids, $message);

            return response()->json([
                    "msg" => "Success",
                    "id" => $user->id,
              //      "gcm" => $result
                ]);
        } else {
            return response()->json([
                    "msg" => "Success",
                    "id" => $user->id
                ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = \App\Models\User::find($id);
        return response()->json($user, 200);
    }

    public function areFriend($id, $id2)
    {
        $user = \App\Models\User::find($id);

        $friends = \App\Models\Friend::where(function ($query) use($id, $id2)
                                        {
                                            $query->where('idAccount1', '=', $id)
                                                  ->where('idAccount2', '=', $id2);
                                        })->orWhere(function ($query) use($id, $id2)
                                        {
                                            $query->where('idAccount2', '=', $id)
                                                  ->where('idAccount1', '=', $id2);
                                        })
                                        ->get();

        $friendRequest = \App\Models\FriendRequest::where(function ($query) use($id, $id2)
                                        {
                                            $query->where('idAccount1', '=', $id)
                                                  ->where('idAccount2', '=', $id2);
                                        })->orWhere(function ($query) use($id, $id2)
                                        {
                                            $query->where('idAccount2', '=', $id)
                                                  ->where('idAccount1', '=', $id2);
                                        })
                                        ->get();
                                        
        return response()->json([
            'account' => $user->toArray(),
            'friends' => sizeof($friends) > 0 ? $friends[0] : false,
            'request' => sizeof($friendRequest) > 0 ? $friendRequest[0] : false
        ]);
    }

    public function friends($id)
    {
        $friends = \App\Models\Friend::where('idAccount1', '=', $id)
                                     ->join('account', 'account.id', '=', 'friend.idAccount2')
                                     ->get(array('account.*'));

        return response()->json($friends, 200);
    }

    public function friends2($id)
    {
        $friends1 = \App\Models\Friend::where('idAccount1', '=', $id)
                                     ->join('account', 'account.id', '=', 'friend.idAccount2')
                                     ->get(array('account.*'));

        $friends2 = \App\Models\Friend::where('idAccount2', '=', $id)
                                     ->join('account', 'account.id', '=', 'friend.idAccount1')
                                     ->get(array('account.*'));

        return response()->json(array_merge($friends1->toArray(), $friends2->toArray()));
    }

    public function friendRequests($id)
    {
        $friends = \App\Models\FriendRequest::where('idAccount2', '=', $id)
                                     ->join('account', 'account.id', '=', 'friend_request.idAccount1')
                                     ->get(array('account.*', 'friend_request.id as IDRequest', 'friend_request.idAccount1', 'friend_request.idAccount2'));

        return response()->json($friends, 200);
    }

    public function find($search, $id)
    {
        $user = \App\Models\User::where('id', '<>', $id)
                                ->where('name', 'LIKE', '%' . trim(str_replace('+', ' ', $search)) . '%')
                                ->orWhere('lastname', 'LIKE', '%' . $search . '%')
                                ->get();

        return response()->json($user, 200);
    }

    public function checkins($user)
    {
        $friends = \App\Models\StoreCheckin::where('idAccount', '=', $user)
                                     ->join('store', 'store.id', '=', 'store_checkin.idStore')
                                     ->get(array('store.*'));

        return response()->json($friends, 200);
    }

    public function storeFriendRequests(Request $request)
    {
        $user = new \App\Models\FriendRequest();
        $user->date = $request->date;
        $user->idAccount1 = $request->idAccount1;
        $user->idAccount2 = $request->idAccount2;
        $user->save();

        $user2 = \App\Models\User::find($request->idAccount2);
        $user1 = \App\Models\User::find($request->idAccount1);
        if ($user2['gcm_regid']) {
            $result = $this->send_notification(array($user2['gcm_regid']), array(
                                        "msg" => "Você tem uma solicitação de amizade.",
                                        "title" => $user1['name'] . " " . $user1["lastname"],
                                        "intent" => "InicioActivity",
                                        "image" => $user1['image']
                                    ));
        }

        return response()->json(['id' => $user->id]);
    }

    public function friendRequestsAccept(Request $request, $id)
    {
        $user = \App\Models\FriendRequest::find($id);
        $user->delete();

        $user = new \App\Models\Friend();
        $user->date = $request->date;
        $user->idAccount1 = $request->idAccount1;
        $user->idAccount2 = $request->idAccount2;
        $user->save();

        return response()->json(['id' => $user->id]);
    }

    public function notification($id, $msg)
    {
        $registatoin_ids = array($id);
        $message = array("price" => $msg);
     
        $result = $this->send_notification($registatoin_ids, $message);
     
        return response()->json(['result' => $result]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user = \App\Models\User::find($id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->ocupation = $request->ocupation;
        $user->birth = $request->birth;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->gcm_regid = $request->gcm_regid;
        $user->save();

        return response()->json([
                "msg" => "Success"
            ]);
    }

    public function password(Request $request, $id, $current, $new)
    {
        $user = \App\Models\User::find($id);
        if ($user->password == $current) {
            $user->password = $new;
            $user->save();

            return response()->json([
                "result" => "Success"
            ]);    
        } else {
            return response()->json([
                "result" => "error"
            ]);    
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = \App\Models\User::find($id);
        $user->delete();

        return response()->json([
                "msg" => "Success"
            ], 200); 
    }

    public function destroyFriend($id)
    {
        $user = \App\Models\Friend::find($id);
        $user->delete();

        return response()->json([
                "msg" => "Success"
            ], 200); 
    }

    public function friendRequestsDelete($id)
    {
        $user = \App\Models\FriendRequest::find($id);
        $user->delete();

        return response()->json([
                "msg" => "Success"
            ], 200); 
    }
}
