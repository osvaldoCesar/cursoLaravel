<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login
// Auth::routes();
Route::post('/authenticate/login/', "Auth\LoginController@login");

Route::group(['middleware' => ['auth']], function () {
    Route::post('/authenticate/logout/', "Auth\LoginController@logout");
    Route::get('/authenticate/getRefrescarUsuarioAutenticado', function () {
        return Auth::user()->load('file');
    });

    // Usuarios

    Route::get("/administracion/usuario/getListarRolPermisosByUsuario", "Administracion\UsersController@getListarRolPermisosByUsuario");
    Route::get("/administracion/usuario/getListarUsuarios",  "Administracion\UsersController@getListarUsuarios");
    Route::post("/administracion/usuario/setRegistrarUsuario", "Administracion\UsersController@setRegistrarUsuario");
    Route::post("/administracion/usuario/setRegistrarUsuario", "Administracion\UsersController@setRegistrarUsuario");
    Route::post("/administracion/usuario/setEditarUsuario", "Administracion\UsersController@setEditarUsuario");
    Route::post("/administracion/usuario/setCambiarEstadoUsuario", "Administracion\UsersController@setCambiarEstadoUsuario");
    Route::post("/administracion/usuario/setEditarRolByUsuario", "Administracion\UsersController@setEditarRolByUsuario");
    Route::get("/administracion/usuario/getRolByUsuario", "Administracion\UsersController@getRolByUsuario");
    Route::get('/administracion/usuario/getListarPermisosByRolAsignado', "Administracion\UsersController@getListarPermisosByRolAsignado");
    Route::get('/administracion/usuario/getListarPermisosByUsuario', "Administracion\UsersController@getListarPermisosByUsuario");
    Route::post('/administracion/usuario/setRegistrarPermisosByUsuario', "Administracion\UsersController@setRegistrarPermisosByUsuario");

    // Roles
    Route::get("/administracion/rol/getListarRoles", "Administracion\RolesController@getListarRoles");
    Route::get("/administracion/rol/getListarPermisosByRol", "Administracion\RolesController@getListarPermisosByRol");
    Route::post("/administracion/rol/setRegistrarRolPermisos", "Administracion\RolesController@setRegistrarRolPermisos");
    Route::post("/administracion/rol/setEditarRolPermisos", "Administracion\RolesController@setEditarRolPermisos");

    // Permisos
    Route::get("/administracion/permiso/getListarPermisos", "Administracion\PermissionController@getListarPermisos");
    Route::post("/administracion/permiso/setRegistrarPermisos", "Administracion\PermissionController@setRegistrarPermisos");
    Route::post("/administracion/permiso/setEditarPermiso", "Administracion\PermissionController@setEditarPermiso");

    // Archivos
    Route::post('/archivo/setRegistrarArchivo', "FilesController@setRegistrarArchivo");
});


Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath')
->where('optional', '.*');
