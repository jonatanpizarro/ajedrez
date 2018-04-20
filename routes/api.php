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
	header("Access-Control-Allow-Origin: *");
    return $request->user();
});

Route::get('/crear_partida/{jugador1}/{jugador2}' , function($jugador1,$jugador2){
	$partida = new Partida();
	$partida->estado=0;
	$partida->jugador1=$jugador1;
	$partida->jugador2=$jugador2;
	header("Access-Control-Allow-Origin: *");
	return ($partida);

});


Route::get('/en_espera/{token}' , function($token){
	
	$espera = User::where('espera', 0)->get();
	header("Access-Control-Allow-Origin: *");
	return $espera;
});

Route::get('/partida/{id_partida}' , function(){

	header("Access-Control-Allow-Origin: *");
	return ($partida);
});

//login
Route::get('/login/{name}/{password}' , function($name, $password){
	//comprueba si es la primera vez que se loguea y se le da un token
	if (Auth::attempt(['name'=> $name, 'password'=>$password])) {	

		if (Auth::attempt(['name'=> $name, 'password'=>$password, 'api_token'=>"0"])) {

			$rand_part = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());			
			User::where('name', $name)->update(['api_token',$rand_part]);
			

			$user = User::where('name',$name )->select('api_token')->get();
			header("Access-Control-Allow-Origin: *");
			return json_encode(array('estado'=>'ok','token' =>$user ));
			}	

		else{
			$user = User::where('name',$name )->select('api_token')->get();
			header("Access-Control-Allow-Origin: *");
			return json_encode(array('estado'=>'ok','token' =>$user ));
			
		}	
	}

	else {
		header("Access-Control-Allow-Origin: *");
		return("registrado");
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
