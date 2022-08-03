<?php

namespace App\Http\Controllers\Configuracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    public function getListarCategorias(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cNombre        =      $request->cNombre;
        $cDescripcion   =      $request->cDescripcion;

        $cNombre     =      ($cNombre     == NULL) ? ($cNombre     = ''): $cNombre;
        $cDescripcion    =      ($cDescripcion    == NULL) ? ($cDescripcion    = ''): $cDescripcion;

        $rpta        =      DB::select('call sp_Categoria_getListarCategorias(?, ?)',
                                                                    [
                                                                        $cNombre,
                                                                        $cDescripcion,
                                                                    ]);
        return $rpta;
    }


    public function setRegistrarCategoria(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cNombre        =      $request->cNombre;
        $cDescripcion   =      $request->cDescripcion;
        $nIdAuthUser    =      Auth::id();

        $cNombre     =      ($cNombre     == NULL) ? ($cNombre     = ''): $cNombre;
        $cDescripcion    =      ($cDescripcion    == NULL) ? ($cDescripcion    = ''): $cDescripcion;

        $rpta        =      DB::select('call sp_Categoria_setRegistrarCategoria(?, ?, ?)',
                                                                    [
                                                                        $cNombre,
                                                                        $cDescripcion,
                                                                        $nIdAuthUser
                                                                    ]);
        return $rpta;
    }
}
