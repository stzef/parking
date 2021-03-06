<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use \App\Convert;
use \App\Models\Movimientos;
use \App\Models\Empresas;
use \App\Models\Tarifas;
use \App\Models\Parametros;
use \App\Models\Tipovehiculo;
use \Auth;
use Codedge\Fpdf\Fpdf\Fpdf;
use DateTime;
use HttpOz\Roles\Traits\HasRole;
class MovimientosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('movimientos/entrada');
    }

    public function salida()
    {
        return view('movimientos/salida');
    }

    public function lista()
    {
        return view('movimientos/list');
    }

    public function tickets()
    {
        return view('movimientos/tickets');
    }

    public function config()
    {
        return view('movimientos/config');
    }

    public function setTime(Request $request){
        $dataBody = $request->all();
        $parametro = Parametros::where('id',6)->first();
        $tarifa = Tarifas::where('ctarifa',(int)$dataBody['ctarifa'])->first();
        $datetime1 = new DateTime($dataBody['salida']['fhentrada']);
        $datetime2 = new DateTime($dataBody['salida']['fhsalida']);
        $interval = date_diff($datetime1, $datetime2);
        $tiempo =(24 * $interval->d) + $interval->h;
        $vrpagar = $tiempo * $tarifa['vrtarifa'];
        if($interval->i > $parametro->value_text){
            $vrpagar += $tarifa['vrtarifa'];
        }
        if($vrpagar == 0){
            $vrpagar = $tarifa['vrtarifa'];
        }
        $vrpagar=round($vrpagar, 0, PHP_ROUND_HALF_UP);
        $Arr = array($vrpagar);
        return response()->json(array("obj" => $Arr));
    }
    public function createEntrada(Request $request){
        $dataBody = $request->all();
        $dataBody['fhentrada'] = new DateTime($dataBody['fhentrada']);
        $dataBody['cusu'] = Auth::user()->id;
        $dataBody['sedes_id'] = Auth::user()->sede_id;
        $dataBody['ctimovi'] = 1;
        $dataBody['placa'] = mb_strtoupper($dataBody['placa']);
        $validator = Validator::make($dataBody,
            [
                'cusu' => 'required|exists:users,id',
                'placa' => 'required|max:15',
                'ctarifa' => 'required|exists:tarifas,ctarifa',
                'ctipov' => 'required|exists:tipovehiculo,ctipov',
                'fhentrada' => 'required',
                'sedes_id' => 'required|exists:sedes,csede',
                'ctimovi' => 'required|exists:timovi,ctimovi'
            ],
            [
                'cusu.required' => 'required',
                'placa.required' => 'required',
                'ctarifa.required' => 'required',
                'ctipov.required' => 'required',
                'fhentrada.required' => 'required',
                'sedes_id.required' => 'required',
                'ctimovi.required' => 'required',
            ]
        );
        if ($validator->fails()){
            $messages = $validator->messages();
            return response()->json(array("errors_form" => $messages),400);
        }else{
            $entrada = Movimientos::create($dataBody);
        }
        return response()->json(array("obj" => $entrada->toArray()));
    }
    public function createSalida(Request $request){
        $dataBody = $request->all();
        $dataBody['cmovi'] = (int)$dataBody['cmovi'];
        $dataBody['fhentrada'] = new DateTime($dataBody['fhentrada']);
        $dataBody['fhsalida'] = new DateTime($dataBody['fhsalida']);
        $dataBody['cusu'] = Auth::user()->id;
        $dataBody['sedes_id'] = Auth::user()->sede_id;
        $dataBody['ctimovi'] = 2;
        $dataBody['ctipov'] = (int)$dataBody['ctipov'];
        $dataBody['ctarifa'] = (int)$dataBody['ctarifa'];
        $dataBody['vrpagar'] = (int)$dataBody['vrpagar'];
        $dataBody['vrdescuento'] = (int)$dataBody['vrdescuento'];
        $movimiento = Movimientos::where('cmovi',$dataBody['cmovi'])->first();
        $validator = Validator::make($dataBody,
            [
                'cusu' => 'required|exists:users,id',
                'placa' => 'required|max:15',
                'ctarifa' => 'required|exists:tarifas,ctarifa',
                'ctipov' => 'required|exists:tipovehiculo,ctipov',
                'fhentrada' => 'required',
                'fhsalida' => 'required',
                'tiempo' => 'required',
                'vrpagar' => 'required',
                'sedes_id' => 'required|exists:sedes,csede',
                'ctimovi' => 'required|exists:timovi,ctimovi'
            ],
            [
                'cusu.required' => 'required',
                'placa.required' => 'required',
                'ctarifa.required' => 'required',
                'ctipov.required' => 'required',
                'fhentrada.required' => 'required',
                'fhsalida.required' => 'required',
                'tiempo.required' => 'required',
                'vrpagar.required' => 'required',
                'sedes_id.required' => 'required',
                'ctimovi.required' => 'required',
            ]
        );
        if ($validator->fails()){
            $messages = $validator->messages();
            return response()->json(array("errors_form" => $messages),400);
        }else{
            $movimiento->fhsalida = $dataBody['fhsalida'];
            $movimiento->ctarifa = $dataBody['ctarifa'];
            $movimiento->ctipov = $dataBody['ctipov'];
            $movimiento->ctimovi = $dataBody['ctimovi'];
            $movimiento->tiempo = $dataBody['tiempo'];
            $movimiento->vrpagar = $dataBody['vrpagar'];
            $movimiento->vrdescuento = $dataBody['vrdescuento'];
            $movimiento->cortesia = $dataBody['cortesia'];
            $salida = $movimiento->save();
        }
        return response()->json(array("obj" => $salida));
    }
    public function saveParams(request $request){
        $dataBody = $request->all();
        foreach ($dataBody as $key => $value) {
            $parametro = Parametros::where('id',$key)->first();
            if($parametro){
                $parametro->value_text = $value;
                $parametro->save();
            }
        }
        return response()->json(array("obj" => $dataBody));
    }
    public function ticketEntrada($cmovi){
        $movimiento = Movimientos::where("cmovi",$cmovi)->first();
        $empresa = Empresas::first();
        $tarifas = Tarifas::all();
        $tipovehiculos = Tipovehiculo::all();
        $parametrosheader = Parametros::where('cparam','like','E%')->get();
        $parametrosfooter = Parametros::where('cparam','like','F%')->get();
        $txttarifas = '';
        $txttipovehiculo = 'Tipos de Vehiculos ';
        $fhentrada = explode(" ", $movimiento->fhentrada);
        $pdf = new Fpdf('P','mm',array(58,185));
        $pdf->AddPage();
        $consecutivofc = Parametros::where('cparam','CF')->first();
        $maxHeight = $pdf->h;
        $maxWidth = $pdf->w;
        $cellHeightHeader = 15;
        $cellHeight = 10;

        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY()-7, $maxWidth*0.93, $pdf->getY()-7);

        $pdf->setY(1);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$empresa->nombre,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,"NIT. ".$empresa->nit.'-'.$empresa->dv,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$empresa->direccion,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,Auth::user()->sede->nsede,0,0,'C');

        $pdf->Line($maxWidth*0.06, $pdf->getY() + 11, $maxWidth*0.93, $pdf->getY() + 11);

        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,utf8_decode('TIQUETE ENTRADA Nº ').$consecutivofc->value_text.str_pad($movimiento->cmovi,7, 0, STR_PAD_LEFT),0,0,'C');

        $pdf->setY($pdf->getY() + 11);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.88, 3,'TARIFAS',0,0,'C');
        $pdf->Ln();
        foreach ($tarifas as $tarifa) {
            $pdf->setX($maxWidth*0.06);
            $pdf->SetFont('Arial', '', 7.8);
            $pdf->Cell($maxWidth*0.88, 3,$tarifa->ntarifa.':'.$tarifa->vrtarifa.' ',0,0,'C');
            $pdf->Ln();
        }

        $pdf->setY($pdf->getY()-3.5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper('tarifa seleccionada'),0,0,'C');

        $pdf->setY($pdf->getY()+5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper($movimiento->tarifa->ntarifa.' ($'.$movimiento->tarifa->vrtarifa.")"),0,0,'C');
        $pdf->Ln(2);

            foreach ($parametrosheader as $parametro) {
                if($parametro->value_text){
                    $pdf->setY($pdf->getY() + 5);
                    $pdf->setX($maxWidth*0.06);
                    $pdf->SetFont('Arial', '', 7.8);
                    $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$parametro->value_text,0,0,'C');
                }
            }


        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 34);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper($movimiento->placa),0,0,'C');

        $pdf->Ln(2);

        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY() + 12, $maxWidth*0.93, $pdf->getY() + 12);

        $pdf->Ln(2);

        $pdf->Code128($maxWidth*0.06,$pdf->getY() + 12,$movimiento->placa,$maxWidth*0.88,$cellHeightHeader);

        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY() + 30, $maxWidth*0.28, $pdf->getY() + 30);

        $pdf->setY($pdf->getY() + 23);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->cell($maxWidth*0.68, $cellHeightHeader,'ENTRADA',0,0,'C');

        $pdf->Line($maxWidth*0.72, $pdf->getY()+7, $maxWidth*0.93, $pdf->getY()+7);

        $pdf->Line($maxWidth*0.058, $pdf->getY()+29, $maxWidth*0.06, $pdf->getY()+7);
        $pdf->Line($maxWidth*0.93, $pdf->getY()+29, $maxWidth*0.93, $pdf->getY()+7);

        $pdf->setY($pdf->getY() + 7);
        $pdf->setX($maxWidth*0.10);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'N. TIQUETE',0,0,'C');
        $pdf->setX($maxWidth*0.55);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$consecutivofc->value_text.str_pad($movimiento->cmovi,7, 0, STR_PAD_LEFT),0,0,'L');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.10);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'FECHA',0,0,'C');
        $pdf->setX($maxWidth*0.55);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhentrada[0],0,0,'L');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.10);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'HORA',0,0,'C');
        $pdf->setX($maxWidth*0.55);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhentrada[1],0,0,'L');

        $pdf->Line($maxWidth*0.06, $pdf->getY()+12, $maxWidth*0.93, $pdf->getY()+12);

        $pdf->Ln(12);
        foreach ($parametrosfooter as $parametro) {
            $pdf->SetLineWidth(1);
            $pdf->Line($maxWidth*0.06, $pdf->getY() + 3, $maxWidth*0.93, $pdf->getY() + 3);

            $pdf->setY($pdf->getY() + 7);
            $pdf->setX($maxWidth*0.06);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->MultiCell($maxWidth*0.88,4,utf8_decode($parametro->value_text),0,'C',0);

        }
        $pdf->Output();
        $this->renderPdf();
        $pdf->Close();
    }
    public function ticketSalida($cmovi){
        $movimiento = Movimientos::where("cmovi",$cmovi)->first();
        $empresa = Empresas::first();
        $tarifas = Tarifas::all();
        $tipovehiculos = Tipovehiculo::all();
        $parametrosheader = Parametros::where('cparam','like','E%')->get();
        $parametrosfooter = Parametros::where('cparam','like','F%')->get();
        $consecutivofc = Parametros::where('cparam','CF')->first();
        $txttarifas = 'Tarifas: ';
        $txttipovehiculo = 'Tipos de Vehiculos ';
        $fhentrada = explode(" ", $movimiento->fhentrada);
        $fhsalida = explode(" ", $movimiento->fhsalida);
        $tiempo = explode(":", $movimiento->tiempo);
        $vrtotal = ($movimiento->vrpagar) - ($movimiento->vrdescuento);
        $pdf = new Fpdf('P','mm',array(58,220));
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $maxHeight = $pdf->h;
        $maxWidth = $pdf->w;
        $cellHeightHeader = 15;
        $cellHeight = 10;

        if($movimiento->cortesia){
            $cortesia = "SI";
        }else{
            $cortesia = "NO";
        }

        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY()-7, $maxWidth*0.93, $pdf->getY()-7);

        $pdf->setY(1);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$empresa->nombre,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,"NIT. ".$empresa->nit.'-'.$empresa->dv,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$empresa->direccion,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,Auth::user()->sede->nsede,0,0,'C');

        $pdf->Line($maxWidth*0.06, $pdf->getY() + 11, $maxWidth*0.93, $pdf->getY() + 11);

        foreach ($tarifas as $tarifa) {
            $txttarifas .= $tarifa->ntarifa.'- $'.$tarifa->vrtarifa.' ';
        }
        foreach ($tipovehiculos as $tipovehiculo) {
            $txttipovehiculo .= $tipovehiculo->detalle.' ';
        }
        $pdf->setY($pdf->getY() + 6);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,utf8_decode('TIQUETE SALIDA Nº ').$consecutivofc->value_text.str_pad($movimiento->cmovi,7, 0, STR_PAD_LEFT),0,0,'C');
        $pdf->Ln(3);

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper($movimiento->placa),0,0,'C');

        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY() + 14, $maxWidth*0.3, $pdf->getY() + 14);

        $pdf->setY($pdf->getY() + 7);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->cell($maxWidth*0.67, $cellHeightHeader,'ENTRADA',0,0,'C');

        $pdf->Line($maxWidth*0.7, $pdf->getY()+7, $maxWidth*0.93, $pdf->getY()+7);

        $pdf->Line($maxWidth*0.058, $pdf->getY()+50, $maxWidth*0.06, $pdf->getY()+7);
        $pdf->Line($maxWidth*0.94, $pdf->getY()+50, $maxWidth*0.94, $pdf->getY()+7);

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'FECHA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhentrada[0],0,0,'L');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'HORA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhentrada[1],0,0,'L');

        //ENTRADA

        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY() + 12, $maxWidth*0.32, $pdf->getY() + 12);

        $pdf->setY($pdf->getY() + 5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->cell($maxWidth*0.65, $cellHeightHeader,'SALIDA',0,0,'C');

        $pdf->Line($maxWidth*0.68, $pdf->getY()+7, $maxWidth*0.94, $pdf->getY()+7);

        $pdf->Line($maxWidth*0.058, $pdf->getY()+50, $maxWidth*0.06, $pdf->getY()+7);
        $pdf->Line($maxWidth*0.94, $pdf->getY()+50, $maxWidth*0.94, $pdf->getY()+7);

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'FECHA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhsalida[0],0,0,'L');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'HORA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhsalida[1],0,0,'L');

        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY() + 12, $maxWidth*0.32, $pdf->getY() + 12);

        $pdf->setY($pdf->getY() + 5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->cell($maxWidth*0.66, $cellHeightHeader,'TIEMPO',0,0,'C');

        $pdf->Line($maxWidth*0.68, $pdf->getY()+7, $maxWidth*0.94, $pdf->getY()+7);

        $pdf->Line($maxWidth*0.058, $pdf->getY()+35, $maxWidth*0.06, $pdf->getY()+7);
        $pdf->Line($maxWidth*0.94, $pdf->getY()+35, $maxWidth*0.94, $pdf->getY()+7);

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'HORAS',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$tiempo[0],0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'MINUTOS',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$tiempo[1],0,0,'C');

        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY() + 12, $maxWidth*0.22, $pdf->getY() + 12);

        $pdf->setY($pdf->getY() + 5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->cell($maxWidth*0.68, $cellHeightHeader,'VALOR A PAGAR',0,0,'C');

        $pdf->Line($maxWidth*0.8, $pdf->getY()+7, $maxWidth*0.94, $pdf->getY()+7);

        $pdf->Line($maxWidth*0.058, $pdf->getY()+36.5, $maxWidth*0.06, $pdf->getY()+7);
        $pdf->Line($maxWidth*0.94, $pdf->getY()+36.5, $maxWidth*0.94, $pdf->getY()+7);

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.1);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($maxWidth*0.79, $cellHeightHeader,number_format($vrtotal,0),0,0,'C');


        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.1);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell($maxWidth*0.79, $cellHeightHeader,"(".Convert::convertir($vrtotal,'pesos').")",0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'Valor Calculado',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->setX($maxWidth*0.36);
        $pdf->Cell($maxWidth*0.75, $cellHeightHeader,number_format($movimiento->vrpagar,0),0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'Descuento',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->setX($maxWidth*0.36);
        $pdf->Cell($maxWidth*0.75, $cellHeightHeader,number_format($movimiento->vrdescuento,0),0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'Cortesia',0,0,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->setX($maxWidth*0.36);
        $pdf->Cell($maxWidth*0.75, $cellHeightHeader,$cortesia,0,0,'C');

        $pdf->Line($maxWidth*0.06, $pdf->getY() + 12, $maxWidth*0.94, $pdf->getY() + 12);

        $pdf->setY($pdf->getY()+9);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper('tarifa seleccionada'),0,0,'C');

        $pdf->setY($pdf->getY()+3);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper($movimiento->tarifa->ntarifa.' ($'.$movimiento->tarifa->vrtarifa.")"),0,0,'C');

        $pdf->setY($pdf->getY() + 11);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell($maxWidth*0.88, 3,'TARIFAS',0,0,'C');
        $pdf->Ln();
        foreach ($tarifas as $tarifa) {
            $pdf->setX($maxWidth*0.06);
            $pdf->SetFont('Arial', '', 7.8);
            $pdf->Cell($maxWidth*0.88, 3,$tarifa->ntarifa.':'.$tarifa->vrtarifa.' ',0,0,'C');
            $pdf->Ln();
        }

        $pdf->Ln();
        foreach ($parametrosfooter as $parametro) {
            $pdf->SetLineWidth(1);
            $pdf->Line($maxWidth*0.06, $pdf->getY()+2, $maxWidth*0.94, $pdf->getY()+2);

            $pdf->setY($pdf->getY()+3);
            $pdf->setX($maxWidth*0.06);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->MultiCell($maxWidth*0.88,4,utf8_decode($parametro->value_text),0,'C',0);

        }
        $pdf->Output();
        $this->renderPdf();
        $pdf->Close();
    }
    public function reportFechas($date1,$date2){
        $movimientos = Movimientos::all();
        //var_dump($movimientos);exit();
        $empresa = Empresas::first();
        $tarifas = Tarifas::all();
        $sumaVP = 0;
        $sumaVD = 0;
        $sumaVC = 0;
        $pdf = new Fpdf('P','mm','letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $maxHeight = $pdf->h;
        $maxWidth = $pdf->w;

        $pdf->SetFont('Courier', 'B', 14);
        $pdf->Cell($maxWidth*0.935, 3,$empresa->nombre,0,0,'C');
        $pdf->SetFont('Courier', 'B', 9);
        $pdf->Ln(5);
        $pdf->Cell($maxWidth*0.935, 3,'NIT.'.$empresa->nit,0,0,'C');
        $pdf->SetFont('Courier', 'B', 10);
        $pdf->Ln(5);
        $pdf->Cell($maxWidth*0.935, 3,'REPORTE DE SALIDAS POR FECHAS',0,0,'C');
        $pdf->Ln(5);
        $pdf->Cell($maxWidth*0.935, 3,'DESDE '.$date1.' - HASTA '.$date2,0,0,'C');

        $pdf->setY($pdf->getY()+10);
        $pdf->setX(1.5);
        $pdf->SetFont('Courier', 'B', 8);
        $pdf->Cell($maxWidth*0.0237, 5,'It',1,0,'C');
        $pdf->Cell($maxWidth*0.0637, 5,'Placa',1,0,'C');
        $pdf->Cell($maxWidth*0.1537, 5,'Fecha Entrada',1,0,'C');
        $pdf->Cell($maxWidth*0.1537, 5,'Fecha Salida',1,0,'C');
        $pdf->Cell($maxWidth*0.1047, 5,'Tiempo(H,M)',1,0,'C');
        $pdf->Cell($maxWidth*0.1137, 5,'Tarifa',1,0,'C');
        $pdf->Cell($maxWidth*0.0737, 5,'Cortesia',1,0,'C');
        $pdf->Cell($maxWidth*0.1037, 5,'Vr.Calculado',1,0,'C');
        $pdf->Cell($maxWidth*0.1037, 5,'Vr.Descuento',1,0,'C');
        $pdf->Cell($maxWidth*0.0937, 5,'Vr.Pagado',1,0,'C');

        $pdf->SetFont('Courier', '', 7.5);
        foreach ($movimientos->getIterator() as $i => $movimiento) {
            $salida = explode(" ", $movimiento->fhsalida);
            if($salida[0] >= $date1 && $salida[0] <= $date2 && $movimiento->ctimovi != 3){
                $pdf->Ln();
                $pdf->setX(1.5);
                $pdf->Cell($maxWidth*0.0237, 5,$i+1,1,0,'C');
                $pdf->Cell($maxWidth*0.0637, 5,$movimiento->placa,1,0,'C');
                $pdf->Cell($maxWidth*0.1537, 5,$movimiento->fhentrada,1,0,'C');
                $pdf->Cell($maxWidth*0.1537, 5,$movimiento->fhsalida,1,0,'C');
                $pdf->Cell($maxWidth*0.1047, 5,$movimiento->tiempo,1,0,'C');
                $pdf->Cell($maxWidth*0.1137, 5,$movimiento->tarifa->ntarifa,1,0,'C');
                if($movimiento->cortesia){
                    $pdf->Cell($maxWidth*0.0737, 5,'Si',1,0,'C');
                }else{
                    $pdf->Cell($maxWidth*0.0737, 5,'No',1,0,'C');
                }
                $pdf->Cell($maxWidth*0.1037, 5,'$ '.number_format($movimiento->vrpagar,0),1,0,'R');
                $pdf->Cell($maxWidth*0.1037, 5,'$ '.number_format($movimiento->vrdescuento,0),1,0,'R');
                $pdf->Cell($maxWidth*0.0937, 5,'$ '.number_format($movimiento->vrpagar - $movimiento->vrdescuento,0),1,0,'R');
                $sumaVP +=$movimiento->vrpagar - $movimiento->vrdescuento;
                $sumaVC +=$movimiento->vrpagar;
                $sumaVD +=$movimiento->vrdescuento;
            }
        }
        $pdf->Ln();
        $pdf->setX(1.5);
        $pdf->SetFont('Courier', 'B', 9);
        $pdf->Cell($maxWidth*0.6869, 5,'TOTAL',1,0,'C');
        $pdf->Cell($maxWidth*0.1037, 5,'$ '.number_format($sumaVC,0),1,0,'R');
        $pdf->Cell($maxWidth*0.1037, 5,'$ '.number_format($sumaVD,0),1,0,'R');
        $pdf->Cell($maxWidth*0.0937, 5,'$ '.number_format($sumaVP,0),1,0,'R');
        $pdf->Output();
        $this->renderPdf();
        $pdf->Close();
    }
    public function renderPdf(){
        header('Content-Type: application/pdf');
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        exit();
    }
}
