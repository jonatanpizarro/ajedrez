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

Route::get('/crear_partida/{jugador1}/{jugador2}/{token}' , function($jugador1,$jugador2){
	$partida = new Partida();
	$partida->estado=0;
	$partida->jugador1=$jugador1;
	$partida->jugador2=$jugador2;
	header("Access-Control-Allow-Origin: *");
	return ($partida);

});


Route::get('/en_espera/{token}' , function($token){
	
	$espera = User::where('espera', 0)->where('api_token','!=',$token)->select('name')->get();
	header("Access-Control-Allow-Origin: *");
	return json_encode(array('estado'=>'ok','nombre' =>$espera ));
	
});

Route::get('/partida/{id_partida}' , function(){

	header("Access-Control-Allow-Origin: *");
	return ($partida);
});

//login
Route::get('/login/{name}/{password}' , function($name, $password){
	//comprueba si es la primera vez que se loguea y se le da un token
	if (Auth::attempt(['name'=> $name, 'password'=>$password])) {	

			$token = User::where('name',$name )->select('api_token')->get();
			//header("Access-Control-Allow-Origin: *");
			//return($token);
			if ($token[0]=="0"){
				$rand_part = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());			
				User::where('name', $name)->update(['api_token',$rand_part]);
				$token1 = User::where('name',$name )->select('api_token')->get();
				header("Access-Control-Allow-Origin: *");
				//return("token nuevo");			
				return json_encode(array('estado'=>'ok','token' =>$token1 ));
			}else{
				header("Access-Control-Allow-Origin: *");		
				//return("ya tiene token");	
				return json_encode(array('estado'=>'ok','token' =>$token ));
			}

			
			//$token = User::where('name',$name )->select('api_token')->get();
			//return($user);
		}
			

	/*else if(Auth::attempt(['name'=> $name, 'password'=>$password])){
			$token = User::where('name',$name )->select('api_token')->get();
			header("Access-Control-Allow-Origin: *");
			return json_encode(array('estado'=>'ok','token' =>$user ));
			
		}	*/
	

	else {
		header("Access-Control-Allow-Origin: *");
		return("no existe");
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
