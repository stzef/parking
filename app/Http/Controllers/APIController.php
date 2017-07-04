<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use parking\Models\Empresas;
use parking\Models\Movimientos;
use parking\Models\Users;
use parking\Models\Sedes;
use parking\Models\Tarifas;
use parking\Models\Timovi;
use parking\Models\Tipovehiculo;
class APIController extends Controller{

	public function tarifas(){
		$tarifas = Tarifas::all();
		return response()->json($tarifas);
	}
}
?>