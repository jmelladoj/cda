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

Route::get('/', 'MedidorController@index');
Route::get('/medidor/registro/{dato_1}/{dato_2}/{dato_3}/{dato_4}/{dato_5}/{dato_6}', 'MedidorController@registrarDatos');

//medidores.test/medidor/registro/1/2/3/4/5/6