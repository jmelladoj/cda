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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Ruta de usuario
Route::get('/usuario/logeado', 'UsuarioController@logeado')->name('Usuario logeado');

//Rutas de perfiles
Route::get('/perfiles/{tipo}', 'PerfilController@index')->name('Listar perfiles');
Route::post('/perfil/crear/actualizar', 'PerfilController@crear_actualizar')->name('Crear o actualizar perfil');
Route::post('/perfil/borrar', 'PerfilController@borrar')->name('Borrar perfil');

//Rutas de lugares
Route::get('/lugares/{tipo}', 'LugarController@index')->name('Listar lugares');
Route::post('/lugar/crear/actualizar', 'LugarController@crear_actualizar')->name('Crear o actualizar lugar');
Route::post('/lugar/borrar', 'LugarController@borrar')->name('Borrar lugar');

//Rutas de inventario
Route::get('/inventario/{tipo}', 'ProductoInventarioController@index')->name('Listar inventario');
Route::post('/inventario/crear/actualizar', 'ProductoInventarioController@crear_actualizar')->name('Crear o actualizar inventario');
Route::post('/inventario/borrar', 'ProductoInventarioController@borrar')->name('Borrar inventario');

//Rutas de inventario
Route::get('/usuarios/{tipo}', 'UsuarioController@index')->name('Listar usuario');
Route::get('/usuarios/unico/{texto}', 'UsuarioController@validar_unico')->name('Validar usuario inexistente');
Route::post('/usuario/crear/actualizar', 'UsuarioController@crear_actualizar')->name('Crear o actualizar usuario');
Route::post('/usuario/borrar', 'UsuarioController@borrar')->name('Borrar usuario');
Route::post('/usuario/actualizar/clave', 'UsuarioController@actualizar_clave')->name('Cambiar clave');



Route::get('/{any}', 'HomeController@index')->where('any', '.*')->name('home');
