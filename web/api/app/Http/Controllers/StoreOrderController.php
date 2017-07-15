<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

class StoreOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($user)
    {
        $stores = \App\Models\StoreOrder::where("idAccount", '=', $user)->get();

        for ($i=0; $i < sizeof($stores); $i++) { 
            $stores[$i]['itens'] = \App\Models\StoreOrderItem::where('store_order_item.idOrder', '=', $stores[$i]['id'])
                                                             ->join('store_menu', 'store_menu.id', '=', 'store_order_item.idItem')
                                                             ->get(array('store_order_item.*', 'store_menu.price', 'store_menu.name', 'store_menu.image', 'store_menu.description'));
        }

        return response()->json($stores->toArray());
    }


    public function store(Request $request)
    {
          $user = new \App\Models\StoreOrder();
          $user->idAccount = $request->idAccount;
          $user->idStore = $request->idStore;
          $user->payment = $request->payment;
          $user->obs = $request->obs;
          $user->troco = $request->troco;
          $user->telefone = $request->telefone;
          $user->endereco = $request->endereco;
          $user->save();
          
          $arr = (array) json_decode($request->itens);

          for ($i=0; $i < sizeof($arr); $i++) { 
              $item = new \App\Models\StoreOrderItem();
              $item->idOrder = $user->id;
              $item->idItem = $arr[$i]->idItem;
              $item->quantity = $arr[$i]->quantity;
              $item->save();
          }

          return response()->json(['id' => $user->id]);
    }
}
