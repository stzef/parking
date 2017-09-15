<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Empresas;
use \App\Models\Movimientos;
use \App\Models\Parametros;
use \App\Models\Users;
use \App\Models\Sedes;
use \App\Models\Tarifas;
use \App\Models\Timovi;
use \App\Models\Tipovehiculo;
use \Auth;
class APIController extends Controller{
	public function tarifas(Request $request){
		if( Auth::user()){
			$tarifas = Tarifas::all();
			return response()->json($tarifas->toArray());
		}else{
			return response()->json('');
		}
	}
	public function tipovehiculo(Request $request){
		$tipovehiculo = Tipovehiculo::all();
		return response()->json($tipovehiculo->toArray());
	}
	public function sedes(Request $request){
		$sedes = Sedes::all();
		return response()->json($sedes->toArray());
	}
	public function params(Request $request){
		$params = Parametros::all();
		return response()->json($params->toArray());
	}
	public function roles(Request $request){
		$roles = \HttpOz\Roles\Models\Role::all();
		return response()->json($roles->toArray());
	}
	public function movimientos(Request $request){
		$dataBody = $request->all();
		if($dataBody){
			$movimientos =  Movimientos::where('ctimovi','=',1)->where('placa','=',$dataBody['placa'])->get();
			$movimientosArr = $movimientos->toArray();
		}else{
			$movimientos = Movimientos::all();
			$movimientosArr = $movimientos->toArray();
		}
		foreach ($movimientosArr as $i => $movimiento) {
			$movimientosArr[$i]['tarifa'] = Tarifas::where('ctarifa',$movimiento['ctarifa'])->first()->toArray();
			$movimientosArr[$i]['tipovehiculo'] = Tipovehiculo::where('ctipov',$movimiento['ctipov'])->first()->toArray();
			$movimientosArr[$i]['timovi'] = Timovi::where('ctimovi',$movimiento['ctimovi'])->first()->toArray();
		}
		return response()->json($movimientosArr);
	}
}
?>
