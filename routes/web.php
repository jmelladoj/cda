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
Route::get('/categorias/{tipo}', 'CategoriaProductoController@index')->name('Listar categorias');
Route::post('/categoria/crear/actualizar', 'CategoriaProductoController@crear_actualizar')->name('Crear o actualizar categoria');
Route::post('/categoria/borrar', 'CategoriaProductoController@borrar')->name('Borrar categoria');

//Rutas de lugares
Route::get('/lugares/{tipo}', 'LugarController@index')->name('Listar lugares');
Route::post('/lugar/crear/actualizar', 'LugarController@crear_actualizar')->name('Crear o actualizar lugar');
Route::post('/lugar/borrar', 'LugarController@borrar')->name('Borrar lugar');

//Rutas de inventario
Route::get('/inventario/{tipo}', 'ProductoInventarioController@index')->name('Listar inventario');
Route::post('/inventario/crear/actualizar', 'ProductoInventarioController@crear_actualizar')->name('Crear o actualizar inventario');
Route::post('/inventario/borrar', 'ProductoInventarioController@borrar')->name('Borrar inventario');
Route::post('/inventario/agregar', 'ProductoInventarioController@agregar_stock')->name('Agregar stock inventario');
Route::post('/inventario/retirar', 'ProductoInventarioController@retirar_stock')->name('Retirar stock inventario');
Route::get('/inventario/detalle/{accion}/{id}', 'ProductoInventarioController@index_detalle')->name('Ver detalle stock');
Route::get('/inventario/stock/salida/{id}', 'ProductoInventarioController@index_salida_detalle')->name('Ver detalle salida stock');

//Rutas de usuarios
Route::get('/usuarios/{tipo}', 'UsuarioController@index')->name('Listar usuario');
Route::get('/usuarios/unico/{texto}', 'UsuarioController@validar_unico')->name('Validar usuario inexistente');
Route::post('/usuario/crear/actualizar', 'UsuarioController@crear_actualizar')->name('Crear o actualizar usuario');
Route::post('/usuario/borrar', 'UsuarioController@borrar')->name('Borrar usuario');
Route::post('/usuario/actualizar/clave', 'UsuarioController@actualizar_clave')->name('Cambiar clave');

//Rutas de proveedores
Route::get('/proveedores/{tipo}', 'ProveedorController@index')->name('Listar proveedores');
Route::post('/proveedor/crear/actualizar', 'ProveedorController@crear_actualizar')->name('Crear o actualizar proveedor');
Route::post('/proveedor/borrar', 'ProveedorController@borrar')->name('Borrar proveedor');

//Rutas de ordenes
Route::get('/ordenes/{tipo}', 'OrdenCompraController@index')->name('Listar ordenes');
Route::post('/orden/crear', 'OrdenCompraController@crear')->name('Crear o actualizar orden');
Route::post('/orden/borrar', 'OrdenCompraController@borrar')->name('Borrar orden');
Route::post('/orden/enviar', 'OrdenCompraController@enviar')->name('Enviar orden');
Route::get('/orden/descargar/{id}', 'OrdenCompraController@descargar')->name('Descargar orden');

//Rutas de noficaciones
Route::get('/notificaciones/{tipo}', 'NotificacionController@index')->name('Listar notificaciones');
Route::post('/notificacion/leer', 'NotificacionController@leer')->name('Leer notificacion');


Route::get('/{any}', 'HomeController@index')->where('any', '.*')->name('home');
