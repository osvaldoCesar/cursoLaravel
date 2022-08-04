<?php

namespace App\Http\Controllers\Operacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    public function getListarPedidos(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cNombre        =      $request->cNombre;
        $cDocumento     =      $request->cDocumento;
        $cPedido        =      $request->cPedido;
        $nIdEstado      =      $request->nIdEstado;


        $cNombre        =    ($cNombre      ==  NULL)  ?  ($cNombre     =   '')  :  $cNombre;
        $cDocumento     =    ($cDocumento   ==  NULL)  ?  ($cDocumento  =   '')  :  $cDocumento;
        $cPedido        =    ($cPedido      ==  NULL)  ?  ($cPedido     =   '')  :  $cPedido;
        $nIdEstado      =    ($nIdEstado    ==  NULL)  ?  ($nIdEstado   =    0)  :  $nIdEstado;

        $rpta        =      DB::select('call sp_Pedido_getListarPedidos(?, ?, ?, ?)',
                                                                    [
                                                                        $cNombre,
                                                                        $cDocumento,
                                                                        $cPedido,
                                                                        $nIdEstado,
                                                                    ]);
        return $rpta;
    }
}
