<?php

use Illuminate\Http\Request;
use App\User;
use App\Partida;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/crear_partida/{jugador1}/{jugador2}' , function($jugador1,$jugador2){
	$partida = new Partida();
	$partida->estado=0;
	$partida->jugador1=$jugador1;
	$partida->jugador2=$jugador2;

	return ($partida);

});


Route::get('/en_espera' , function(){
	
	$espera = User::where('espera', 0)->get();
	
	return $espera;
});

Route::get('/partida/{id_partida}' , function(){

	return ($partida);
});


Route::get('/login/{jugador1}/{password}' , function($jugador1, $password){
	if (Auth::attempt('id'=> $jugador1, 'password'=>$password)) {
		return("aaa");
	}

	
});


//ID cuando entras partida
Route::get('/identificadorUsuario/{nick}/{email}' , function($nick , $email){
	$usuario = new Usuario();
	$usuario->id =1;
	$usuario->name="Cyka";
});

Route::get('/mou/{id_partida}/{fila_ini}/{col_ini}/{fila_dest}/{col_dest}' , function($id_partida , $fila_ini , $col_ini , $fila_dest, $col_dest){

});
