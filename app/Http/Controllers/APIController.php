<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Empresas;
use \App\Models\Movimientos;
use \App\Models\Users;
use \App\Models\Sedes;
use \App\Models\Tarifas;
use \App\Models\Timovi;
use \App\Models\Tipovehiculo;
class APIController extends Controller{

	public function tarifas(){
		$tarifas = Tarifas::all();
		return response()->json($tarifas->toArray());
	}

	public function tipovehiculo(){
		$tipovehiculo = Tipovehiculo::all();
		return response()->json($tipovehiculo->toArray());
	}
}
?>