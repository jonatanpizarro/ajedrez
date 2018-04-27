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
	
	User::where('api_token', $token)->update(['espera'=>0]);
	User::where('id', $jugador2)->update(['espera'=>0]);



	$partida = new Partida();
	$partida->estado="empieza";
	$partida->jugador1 =$jugador1;
	$partida->jugador2=$jugador2;
	$partida->save();


	header("Access-Control-Allow-Origin: *");
	return ($partida);

});



Route::get('/comprovar_partida/{jugador1}/{token}' , function($jugador1,$token){	

	if (Partida::where(['jugador1'=>$jugador1])) {

		header("Access-Control-Allow-Origin: *");
		return json_encode(array('estado'=>'Partida encontrada'));
		
	}else if(Partida::where(['jugador2'=>$jugador1])){
		header("Access-Control-Allow-Origin: *");
		return json_encode(array('estado'=>'Partida encontrada'));

	}else{
		header("Access-Control-Allow-Origin: *");
		return json_encode(array('estado'=>'No encontrada'));


	}



	header("Access-Control-Allow-Origin: *");
	return ($partida);

});


Route::get('/en_espera/{token}' , function($token){
	
	$espera = User::where('espera', 1)->where('api_token','!=',$token)->select('name')->get();
	$id=User::where('espera', 1)->where('api_token','!=',$token)->select('id')->get();
	
	header("Access-Control-Allow-Origin: *");
	return json_encode(array('estado'=>'ok','nombre' =>$espera , 'id' =>$id));
	
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

				$id=User::where('name',$name )->select('id')->pluck('id');

				$token1 = User::where('name',$name )->select('api_token')->pluck('api_token');

				header("Access-Control-Allow-Origin: *");						
				
				return json_encode(array('estado'=>'ok','token' =>$token1, 'id'=>$id ));
			}else{
				User::where('name', $name)->update(['espera'=>1]);
				$id=User::where('name',$name )->select('id')->pluck('id');
				header("Access-Control-Allow-Origin: *");	
					
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
