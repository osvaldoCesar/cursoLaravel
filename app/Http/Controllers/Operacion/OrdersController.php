<?php

namespace App\Http\Controllers\Operacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
    public function setRegistrarPedido(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdCliente     =      $request->nIdCliente;
        $cComentario    =      $request->cComentario;
        $fTotalPedido   =      $request->fTotalPedido;
        $listPedido     =      $request->listPedido;
        $nIdAuthUser    =      Auth::id();

        $cComentario     =      ($cComentario ==  NULL) ? ($cComentario    = ''): $cComentario;

        try {
            // Irá todo el código que deseamos realizar y se ejecuta el commit
            DB::beginTransaction();

            $rpta        =      DB::select('call sp_Pedido_setRegistrarPedido(?, ?, ?, ?)',
                                                                        [
                                                                            $nIdCliente,
                                                                            $cComentario,
                                                                            $fTotalPedido,
                                                                            $nIdAuthUser
                                                                        ]);

            $nIdPedido =   $rpta[0]->nIdPedido;

            $listPedido       =    $request->listPedido;
            $listPedidoSize   =    sizeof($listPedido);
            if ($listPedidoSize > 0) {
                foreach ($listPedido as $key => $value) {
                    DB::select('call sp_Pedido_setRegistrarDetallePedido(?, ?, ?, ?)',
                                                                [
                                                                    $nIdPedido,
                                                                    $value['nIdProducto'],
                                                                    $value['nStock'],
                                                                    $value['fPrecio'],
                                                                ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            // Capturará algún error ocurrido en el try y se ejecuta el rollback
            DB::rollBack();
        }
    }
}
