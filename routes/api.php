<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BandejaController;
use App\Http\Controllers\CarpetaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DepartamentoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
	'middleware' => ['api']
], function () {
   Route::group([
      'prefix'=>'auth'],function(){
         Route::post('login/{conexion}','AuthController@login');
         Route::post('/reset-password','AuthController@sendResetPasswordEmail');
         Route::middleware(['auth:api'])->group(function () {
            Route::get('/me', 'AuthController@me')->name('me');
            Route::get('/logout', 'AuthController@logout');
            // Route::get('/revoketoken', [AuthController@RevokeToken']);
            Route::post('/changepassword', 'AuthController@changePassword');
         });
   });
});

// ROUTE BANDEJA 
Route::group([
	'middleware'  => 'api',
  'prefix'      => 'bandeja'
], function () {
  Route::middleware(['auth:api'])->group(function () {
      Route::get('/enviados', 'BandejaController@enviados');
      Route::get('/recibidos', 'BandejaController@recibidos');
      Route::get('/borradores', 'BandejaController@borradores');
      Route::get('/por-corregir', 'BandejaController@corregir');
      Route::get('/count', 'BandejaController@bandeja');
      Route::get('/verificate', 'BandejaController@hasNewDocuments');
  });
});

// ROUTE DEPARTAMENTOS 
Route::group([
	'middleware'  => 'api',
  'prefix'      => 'departamentos'
], function () {

  Route::middleware(['auth:api'])->group(function () {
      Route::get('/', 'DepartamentoController@index');
      Route::post('/', 'DepartamentoController@store');
    //   Route::get('/{id}', 'show');
      Route::post('/{id}', 'DepartamentoController@update');
      Route::delete('/{id}', 'DepartamentoController@destroy');
      Route::get('/list/redactar', 'DepartamentoController@departamentsWrite');
  });
});

// ROUTE NUCLEO 
Route::group([
	'middleware'  => 'api',
  'prefix'      => 'nucleo'
], function () {

  Route::middleware(['auth:api'])->group(function () {
      Route::get('/', 'DepartamentoController@allNucleos');
      Route::get('/byDepartamentos', 'DepartamentoController@departamentsByNucleo');
  });
});

// ROUTE DOCUMENT 
Route::group([
	'middleware'  => 'api',
  'prefix'      => 'documento'
], function () {

  Route::middleware(['auth:api'])->group(function () {
    Route::post('/enviar', 'DocumentoController@store');
    Route::post('/crear-temporal', 'DocumentoController@storeTemporal');
    Route::get('/{id}', 'DocumentoController@show');
    Route::post('/actualizar/{id}', 'DocumentoController@update');
    Route::post('/eliminar-anexo/{id}', 'DocumentoController@destroyAnexo');
    Route::post('/agregar-anexo', 'DocumentoController@addAnexo');
    Route::get('/descargar-anexo/{id}', 'DocumentoController@downloadAnexo');
    Route::get('/generar-documento/{id}', 'DocumentoController@genareteDocument');
    Route::delete('/eliminar-documento/{id}', 'DocumentoController@destroyDocument');
  });
});

// ROUTE PERSONAL 
Route::group([
    'middleware'  => 'api',
    'prefix'      => 'personal'
], function () {

  Route::middleware(['auth:api'])->group(function () {
      Route::get('/', 'PersonalController@index');
      Route::post('/', 'PersonalController@store');
      Route::post('/{id}', 'PersonalController@update');
      Route::delete('/{id}', 'PersonalController@destroy');
      Route::get('/search', 'PersonalController@search');
  });
});

// ROUTES USERS 
Route::group([
	'middleware' => 'api'
], function () {
	Route::group([
		'prefix'=>'users'],function(){
			Route::middleware(['auth:api'])->group(function () {
				Route::get('/', 'UserController@index');
                Route::get('backup', 'UserController@backupDownload');
                Route::post('/', 'UserController@store');
                Route::post('/{id}/personal/{personal}', 'UserController@update');
                Route::post('/actualizar/{id}/contrasena', 'UserController@update_password');
                Route::get('/roles', 'UserController@roles');
                Route::get('/niveles', 'UserController@nivel');
                Route::get('/show/{id}', 'UserController@show');
                // Route::get('/{id}/cerrar', [UserController@cerrar_sesion']);
                Route::delete('/{id}', 'UserController@delete');
			});
		});
});

// ROUTE GRUPOS 
Route::group([
	'middleware'  => 'api',
  'prefix'      => 'grupos'
], function () {
  Route::middleware(['auth:api'])->group(function () {
      Route::get('/', 'GrupoController@index');
      Route::post('/', 'GrupoController@store');
      Route::get('/{id}', 'GrupoController@show');
      Route::post('/{id}', 'GrupoController@update');
      Route::post('/departamento/{id}', 'GrupoController@addDepartamento');
      Route::delete('/{id}', 'GrupoController@destroy');
      Route::delete('/departamento/{id}', 'GrupoController@destroyDepartamento');  
  });
});



// ROUTE CONFIG
Route::group([
	'middleware'  => 'api',
  'prefix'      => 'configuracion'
], function () {

  Route::middleware(['auth:api'])->group(function () {
      Route::post('/firma', 'ConfiguracionController@createFirma');
    //   Route::post('/', 'store');
    //   Route::get('/{id}', 'show');
    //   Route::post('/{id}', 'update');
    //   Route::post('/departamento/{id}', 'addDepartamento');
    //   Route::delete('/{id}', 'destroy');
    //   Route::delete('/departamento/{id}', 'destroyDepartamento');
  });
});

// ROUTE CARPETA
Route::group([
	'middleware'  => 'api',
  'prefix'      => 'carpetas'
], function () {

  Route::middleware(['auth:api'])->group(function () {
      Route::get('/', 'CarpetaController@index');
      Route::post('/', 'CarpetaController@store');
      Route::post('/{id}', 'CarpetaController@update');
      Route::delete('/{id}', 'CarpetaController@delete');
  });
});


