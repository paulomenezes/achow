<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Controllers\Controller;

    use Illuminate\Support\Facades\Hash;

    use DB;

    class StoreController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index($type, $city, $sub)
        {
            $users = \App\Models\Store::where('city', '=', $city)
                                      ->where('idStoreType', '=', $type)
                                      ->where('subtype', 'LIKE', '%, ' . $sub . ',%')
                                      ->get();

            return response()->json($users->toArray());
        }

        public function visited()
        {
            $users = \App\Models\Store::select(DB::raw('count(store_visited.idStore) as visited, store.*'))
                                      ->leftJoin('store_visited', function ($join)
                                      {
                                            $join->on('store_visited.idStore', '=', 'store.id')
                                                ->where('store_visited.idVisitedType', '=', '1');
                                      })
                                      ->orderBy('visited', 'desc')
                                      ->groupBy('store.id')
                                      ->get();

            return response()->json($users->toArray());
        }

        public function extras($store)
        {
            $users = \App\Models\StoreExtra::where('idStore', '=', $store)->get();

            return response()->json($users->toArray());
        }

        public function schedule($store)
        {
            $users = \App\Models\StoreSchedule::where('idStore', '=', $store)->get();

            return response()->json($users->toArray());
        }

        public function menu($store)
        {
            $users = \App\Models\StoreMenu::where('idStore', '=', $store)->orderBy('type', 'asc')->get();

            return response()->json($users->toArray());
        }

        public function find($search)
        {
            $user = \App\Models\Store::where('name', 'LIKE', '%' . $search . '%')
                                     ->get();
                                    
            return response()->json($user, 200);
        }

        public function checkinStore(Request $request)
        {
            $user = new \App\Models\StoreCheckin();
            $user->idAccount = $request->idAccount;
            if ($request->idStore) {
                $user->idStore = $request->idStore;
            } else {
                $user->idShows = $request->idShows;
            }
            $user->message = $request->message;
            if ($request->users) {
                $user->users = $request->users;

                $user1 = \App\Models\User::find($request->idAccount);

                $u = explode(',', $request->users);
                for ($i=0; $i < sizeof($u); $i++) { 
                    $user2 = \App\Models\User::find(trim($u[$i]));

                    if ($user2) {
                        if ($user2['gcm_regid']) {
                            $result = $this->send_notification(array($user2['gcm_regid']), array(
                                                        "msg" => "Marcou um encontro com você.",
                                                        "title" => $user1['name'] . " " . $user1["lastname"],
                                                        "intent" => "InicioActivity",
                                                        "image" => $user1['image'],
                                                        "name" => "fragment",
                                                        "value" => "1"
                                                    ));
                        }

                        $user->save();

                        $notification = new \App\Models\Notification();
                        $notification->message = $request->message;
                        $notification->read = 0;
                        if ($request->idStore) {
                            $notification->idStore = $request->idStore;
                        } else {
                            $notification->idShows = $request->idShows;
                        }
                        $notification->idCheckin = $user->id;
                        $notification->idusersend = $request->idAccount;
                        $notification->iduserreceiver = $user2['id'];
                        $notification->save();
                    }
                }
            }
            $user->date = $request->date;
            $user->save();

            return response()->json(['id' => $user->id]);
        }
    }
