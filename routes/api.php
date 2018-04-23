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

Route::get('/crear_partida/{jugador1}/{jugador2}/{token}' , function($jugador1,$jugador2,$token){
	
	User::where('api_token', $token)->update(['espera'=>2]);
	User::where('id', $jugador2)->update(['espera'=>2]);



	header("Access-Control-Allow-Origin: *");
	return ($partida);

});


Route::get('/en_espera/{token}' , function($token){
	
	$espera = User::where('espera', 1)->where('api_token','!=',$token)->select('name')->get();
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

			$token = User::where('name',$name )->select('api_token')->pluck('api_token');
			
			if ($token[0]=="0"){
				$rand_part = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());	

				User::where('name', $name)->update(['api_token'=>$rand_part]);

				User::where('name', $name)->update(['espera'=>1]);

				$id=User::where('name',$name )->select('id')->get();

				$token1 = User::where('name',$name )->select('api_token')->get();

				header("Access-Control-Allow-Origin: *");						
				
				return json_encode(array('estado'=>'ok','token' =>$token1, 'id'=>$id ));
			}else{
				User::where('name', $name)->update(['espera'=>1]);
				$id=User::where('name',$name )->select('id')->pluck('id');
				header("Access-Control-Allow-Origin: *");	
				//return("ya tiene");			
				return json_encode(array('estado'=>'ok','token' =>$token, 'id'=>$id ));
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
