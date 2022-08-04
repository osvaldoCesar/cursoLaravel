<?php

namespace App\Http\Controllers\Configuracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function getListarProductos(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdProducto       =      $request->nIdProducto;
        $cNombre           =      $request->cNombre;
        $cDescripcion      =      $request->cDescripcion;
        $nIdCategoria      =      $request->nIdCategoria;


        $nIdProducto       =    ($nIdProducto   ==  NULL)  ?  ($nIdProducto   =   0)   :  $nIdProducto;
        $cNombre           =    ($cNombre       ==  NULL)  ?  ($cNombre       =   '')  :  $cNombre;
        $cDescripcion      =    ($cDescripcion  ==  NULL)  ?  ($cDescripcion  =   '')  :  $cDescripcion;
        $nIdCategoria      =    ($nIdCategoria  ==  NULL)  ?  ($nIdCategoria  =   0)   :  $nIdCategoria;

        $rpta        =      DB::select('call sp_Producto_getListarProductos(?, ?, ?, ?)',
                                                                    [
                                                                        $nIdProducto,
                                                                        $cNombre,
                                                                        $cDescripcion,
                                                                        $nIdCategoria,
                                                                    ]);
        return $rpta;
    }
    public function setRegistrarProducto(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cNombre            =      $request->cNombre;
        $cDescripcion       =      $request->cDescripcion;
        $cStock             =      $request->cStock;
        $fPrecio            =      $request->fPrecio;
        $nIdCategoria       =      $request->nIdCategoria;
        $nIdAuthUser        =      Auth::id();

        $cNombre            =    ($cNombre      ==  NULL)  ?  ($cNombre         =   '') :  $cNombre;
        $cDescripcion       =    ($cDescripcion ==  NULL)  ?  ($cDescripcion    =   '') :  $cDescripcion;
        $cStock             =    ($cStock       ==  NULL)  ?  ($cStock          =   0)  :  $cStock;
        $fPrecio            =    ($fPrecio      ==  NULL)  ?  ($fPrecio         =   0)  :  $fPrecio;
        $nIdCategoria       =    ($nIdCategoria ==  NULL)  ?  ($nIdCategoria    =   0)  :  $nIdCategoria;

        $rpta        =      DB::select('call sp_Producto_setRegistrarProducto(?, ?, ?, ?, ?, ?)',
                                                                    [
                                                                        $cNombre,
                                                                        $cDescripcion,
                                                                        $cStock,
                                                                        $fPrecio,
                                                                        $nIdCategoria,
                                                                        $nIdAuthUser,
                                                                    ]);
        return $rpta;
    }
}
