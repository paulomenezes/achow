<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($chat)
    {
        $stores = \App\Models\ChatMessenger::where("idchat", '=', $chat)->orderBy('id', 'asc')->get();

        return response()->json($stores->toArray());
    }

    public function room($room)
    {
        $stores = \App\Models\ChatMessenger::join('account', 'account.id', '=', 'chat_messenger.iduser')
                                           ->where("chat_messenger.idroom", '=', $room)
                                           ->orderBy('chat_messenger.id', 'asc')
                                           ->get(array('chat_messenger.*', 'account.name', 'account.lastname', 'account.image', 'account.facebookID'));

        return response()->json($stores->toArray());
    }

    public function store(Request $request)
    {
        $user = new \App\Models\ChatMessenger();
        $user->text = $request->text;
        $user->iduser = $request->iduser;
        $user->platform = 1;
        if ($request->idchat) {
            $user->idchat = $request->idchat;

            $user1 = \App\Models\Friend::find($request->idchat);
            if ($user1->idAccount1 == $request->iduser) {
                $user2 = \App\Models\User::find($user1->idAccount2); // eu
                $user0 = \App\Models\User::find($user1->idAccount1); // saulo
            } else {
                $user2 = \App\Models\User::find($user1->idAccount1);
                $user0 = \App\Models\User::find($user1->idAccount2);
            }

            if ($user2['gcm_regid']) {
                $result = $this->send_notification(array($user2['gcm_regid']), array(
                                            "msg" => "Você tem uma nova mensagem.",
                                            "title" => $user0['name'] . " " . $user0["lastname"],
                                            "intent" => "ChatActivity",
                                            "image" => $user0['image'],
                                            "name" => "accountGet",
                                            "value" => $user2['id']
                                        ));
            }

            // $notification = new \App\Models\Notification();
            // $notification->message = "Você tem uma nova mensagem.";
            // $notification->read = 0;
            // $notification->idusersend = $request->iduser;
            // $notification->iduserreceiver = $user2['id'];
            // $notification->save();
        } else {
            $user->idroom = $request->idroom;
        }
        $user->save();

        return response()->json(['id' => $user->id]);
    }

    public function destroy($id)
    {
        $user = \App\Models\ChatMessenger::find($id);
        $user->delete();

        return response()->json([
                "msg" => "Success"
            ], 200); 
    }
}
