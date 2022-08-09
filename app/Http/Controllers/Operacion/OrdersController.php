<?php

namespace App\Http\Controllers\Operacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PDF;

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
            return $nIdPedido;
        } catch (Exception $e) {
            // Capturará algún error ocurrido en el try y se ejecuta el rollback
            DB::rollBack();
        }
    }

    public function setGenerarDocumento(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $nIdPedido  =   $request->nIdPedido;

        $logo       =   public_path('img/AdminLTELogo.png');

        $rpta1       =   DB::select('call sp_Pedido_getPedido (?)',
                                                                    [
                                                                        $nIdPedido
                                                                    ]);

        $rpta2       =   DB::select('call sp_Pedido_getDetallePedido (?)',
                                                                    [
                                                                        $nIdPedido
                                                                    ]);

        $pdf = PDF::loadView('reportes.pedido.pdf.ver', [
            'rpta1' =>  $rpta1,
            'rpta2' =>  $rpta2,
            'logo'  =>  $logo
        ]);
        return $pdf->download('invoice.pdf');
    }

    public function setCambiarEstadoPedido(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdPedido          =      $request->nIdPedido;
        $cEstado            =      $request->cEstado;
        $nIdAuthUser        =      Auth::id();

        $nIdPedido  =   ($nIdPedido   ==   NULL) ? ($nIdPedido  =   0)   :   $nIdPedido;
        $cEstado    =   ($cEstado     ==   NULL) ? ($cEstado    =   0)   :   $cEstado;

        DB::select('call sp_Pedido_setCambiarEstadoPedido(?, ?, ?)',
                                                                    [
                                                                        $nIdPedido,
                                                                        $cEstado,
                                                                        $nIdAuthUser,
                                                                    ]);
    }
}
