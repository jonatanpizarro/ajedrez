<?php

use Illuminate\Http\Request;
use App\User;
use App\Partida;
use App\Fichas;

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

	$idP=Partida::where('jugador1',$jugador1)->select('id')->pluck('id');

	$ficha = new Fichas();
	$ficha->posicionIni="B1";
	$ficha->posicionFin="B1";
	$ficha->nombreFicha="Rey";
	$ficha->jugador=$jugador1;
	$ficha->id_partida=$idP[0];
	$ficha->save();

	$ficha1 = new Fichas();
	$ficha1->posicionIni="B8";
	$ficha1->posicionFin="B8";
	$ficha1->nombreFicha="Rey";
	$ficha1->jugador=$jugador2;
	$ficha1->id_partida=$idP[0];
	$ficha1->save();



	header("Access-Control-Allow-Origin: *");
	return ($partida);

});



Route::get('/comprovar_partida/{jugador1}/{token}' , function($jugador1,$token){	

	$partida=Partida::select('jugador1')->pluck('jugador1');
	$partida1=Partida::select('jugador2')->pluck('jugador2');

	if ($partida[0]==$jugador1) {
		$idPartida=Partida::where('jugador1' , $jugador1)->select('id')->pluck('id');
		

		header("Access-Control-Allow-Origin: *");
		return json_encode(array('estado'=>'Partida encontrada' , 'idPartida' =>$idPartida));
		
	}else if($partida1[0]==$jugador1){
		$idPartida=Partida::where('jugador2' , $jugador1)->select('id')->pluck('id');

		header("Access-Control-Allow-Origin: *");
		return json_encode(array('estado'=>'Partida encontrada' , 'idPartida' =>$idPartida));

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


		}
			

	

	else {
		header("Access-Control-Allow-Origin: *");
		return("no existe");
	}
});





Route::get('/mou/{jugador}/{id_partida}/{pos_ini}/{pos_dest}' , function($jugador,$id_partida , $pos_ini ,$pos_dest){

	$fila=$pos_dest[0];
	$column=$pos_dest[1];
	if ($pos_ini[0]==$fila || $pos_ini[1]==$column ){
		
		$posicion1=Fichas::where('jugador',$jugador)->where('id_partida',$id_partida)->select('posicionIni')->get();
		if ($posicion1[0]['posicionIni']==$pos_ini) {

			$posicionIni=Fichas::where('jugador',$jugador)->where('id_partida',$id_partida)->select('posicionIni')->get();


			Fichas::where('jugador',$jugador)->where('id_partida',$id_partida)->update(['posicionIni'=>$pos_dest]);

			$posicionFin=Fichas::where('jugador',$jugador)->where('id_partida',$id_partida)->select('posicionIni')->get();

			header("Access-Control-Allow-Origin: *");	
			return json_encode(array('estado'=>'ok','posIni'=>$posicionIni[0]['posicionIni'],'posFin'=>$posicionFin[0]['posicionIni'] ));
			
			
		}



	}
	
	//$posicion2=Fichas::where('jugador',$jugador)->where('id_partida',$id_partida)->select('posicionIni')->get();
	$posicionFin=Fichas::where('jugador',$jugador)->where('id_partida',$id_partida)->select('posicionIni')->get();
	//$posicion=Fichas::where('jugador',$jugador)->where('id_partida',$id_partida)->select('posicionFin')->get();
	
	

	header("Access-Control-Allow-Origin: *");	
	return json_encode(array('estado'=>'no','posIni'=>$posicion1[0]['posicionIni'],));

	

});


Route::get('/ver/{jugador}/{id_partida}' , function($jugador,$id_partida){

	$posicion1=Fichas::where('jugador',$jugador)->where('id_partida',$id_partida)->select('posicionIni')->get();

	$pos1=Fichas::where('jugador','!=',$jugador)->where('id_partida',$id_partida)->select('posicionIni')->get();


	header("Access-Control-Allow-Origin: *");		
	return json_encode(array('estado'=>'ok','pos'=>$posicion1[0]['posicionIni'],'pos1'=>$pos1[0]['posicionIni']));

});

