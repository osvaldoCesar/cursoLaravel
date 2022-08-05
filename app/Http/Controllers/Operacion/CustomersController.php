<?php

namespace App\Http\Controllers\Operacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    public function getListarClientes(Request $request){
        if(!$request->ajax()) return redirect('/');

        // $cNombre        =      $request->cNombre;
        // $cDocumento     =      $request->cDocumento;
        // $cPedido        =      $request->cPedido;
        // $nIdEstado      =      $request->nIdEstado;


        // $cNombre        =    ($cNombre      ==  NULL)  ?  ($cNombre     =   '')  :  $cNombre;
        // $cDocumento     =    ($cDocumento   ==  NULL)  ?  ($cDocumento  =   '')  :  $cDocumento;
        // $cPedido        =    ($cPedido      ==  NULL)  ?  ($cPedido     =   '')  :  $cPedido;
        // $nIdEstado      =    ($nIdEstado    ==  NULL)  ?  ($nIdEstado   =    0)  :  $nIdEstado;

        $rpta        =      DB::select('call sp_Cliente_getListarClientes');
        return $rpta;
    }
    public function setRegistrarCliente(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cDocumento     =      $request->cDocumento;
        $cNombre        =      $request->cNombre;
        $cApellido      =      $request->cApellido;
        $cEmail         =      $request->cEmail;
        $cTelefono      =      $request->cTelefono;
        $nIdAuthUser    =      Auth::id();

        $cDocumento     =    ($cDocumento   ==  NULL)  ?  ($cDocumento  =   '')  :  $cDocumento;
        $cNombre        =    ($cNombre      ==  NULL)  ?  ($cNombre     =   '')  :  $cNombre;
        $cApellido      =    ($cApellido    ==  NULL)  ?  ($cApellido   =   '')  :  $cApellido;
        $cEmail         =    ($cEmail       ==  NULL)  ?  ($cEmail      =   '')  :  $cEmail;
        $cTelefono      =    ($cTelefono    ==  NULL)  ?  ($cTelefono   =   '')  :  $cTelefono;

        $rpta        =      DB::select('call sp_Cliente_setRegistrarCliente(?, ?, ?, ?, ?, ?)',
                                                                    [
                                                                        $cDocumento,
                                                                        $cNombre,
                                                                        $cApellido,
                                                                        $cEmail,
                                                                        $cTelefono,
                                                                        $nIdAuthUser
                                                                    ]);
        return $rpta;
    }
}
