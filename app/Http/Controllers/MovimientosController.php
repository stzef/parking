<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use \App\Models\Movimientos;
use \App\Models\Empresas;
use \App\Models\Tarifas;
use \App\Models\Parametros;
use \App\Models\Tipovehiculo;
use \Auth;
use Codedge\Fpdf\Fpdf\Fpdf;
use DateTime;
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

    public function list()
    {
        return view('movimientos/list');
    }

    public function getTime($date1,$date2,$tarifa){
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = date_diff($datetime1, $datetime2);
        if ($tarifa->ctarifa == 1) {
            $tiempo = (1440 * $interval->d ) + (60 * $interval->h) + $interval->i ;
            $vrpagar = $tiempo * $tarifa->vrtarifa;
        }elseif ($tarifa->ctarifa == 2) {
            $tiempo =(24 * $interval->d) + $interval->h;
            $vrpagar = $tiempo * $tarifa->vrtarifa; 
        }elseif ($tarifa->ctarifa == 3){
            $vrpagar = $interval->d * $tarifa->vrtarifa;
        }
        $Arr = array('Tiempo' => $interval->format('%H:%I:%S') , 'Valor' => $vrpagar );
        return $Arr;
    }

    public function createEntrada(Request $request){
        $dataBody = $request->all();
        $dataBody['fhentrada'] = new DateTime($dataBody['fhentrada']);
        $dataBody['cusu'] = Auth::user()->id;
        $dataBody['sedes_id'] = Auth::user()->sede_id;
        $dataBody['ctimovi'] = 1;
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
        $movimiento = Movimientos::where('cmovi',$dataBody['cmovi'])->first();
        $dataBody['fhentrada'] = $movimiento->fhentrada;
        $Arr = $this->getTime($dataBody['fhentrada'],$dataBody['fhsalida'],$movimiento->tarifa);
        $dataBody['fhsalida'] = new DateTime($dataBody['fhsalida']);
        $dataBody['cusu'] = Auth::user()->id;
        $dataBody['sedes_id'] = Auth::user()->sede_id;
        $dataBody['ctimovi'] = 2;
        $dataBody['tiempo'] = $Arr['Tiempo'];
        $dataBody['vrpagar'] = $Arr['Valor'];
        $dataBody['ctarifa'] = $movimiento->ctarifa;
        $dataBody['ctipov'] = $movimiento->ctipov;
        $dataBody['ctarifa'] = $movimiento->ctarifa;
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
            $salida = Movimientos::create($dataBody);
        }
        return response()->json(array("obj" => $salida->toArray()));

    }
    public function ticketEntrada($cmovi){
        $movimiento = Movimientos::where("cmovi",$cmovi)->first();
        $empresa = Empresas::first();
        $tarifas = Tarifas::all();
        $tipovehiculos = Tipovehiculo::all();
        $parametrosheader = Parametros::where('cparam','like','E%')->get();
        $parametrosfooter = Parametros::where('cparam','like','F%')->get();
        $txttarifas = 'Tarifas ';
        $txttipovehiculo = 'Tipos de Vehiculos ';
        $fhentrada = explode(" ", $movimiento->fhentrada);
        $pdf = new Fpdf('P','mm',array(82,200));
        $pdf->AddPage();
        $maxHeight = $pdf->h;
        $maxWidth = $pdf->w;
        $cellHeightHeader = 15;
        $cellHeight = 10;
        
        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY(), $maxWidth*0.93, $pdf->getY());
        
        $pdf->setY(7);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$empresa->nombre,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,"NIT. ".$empresa->nit,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$empresa->direccion,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,Auth::user()->sede->nsede,0,0,'C');

        $pdf->Line($maxWidth*0.06, $pdf->getY() + 11, $maxWidth*0.93, $pdf->getY() + 11);
        
        foreach ($tarifas as $tarifa) {
            $txttarifas .= $tarifa->ntarifa.'-'.$tarifa->vrtarifa.' ';
        }
        foreach ($tipovehiculos as $tipovehiculo) {
            $txttipovehiculo .= $tipovehiculo->detalle.' ';
        }

        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$txttarifas,0,0,'C');

        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper($movimiento->tipovehiculo->ntipov.' $'.$movimiento->tarifa->vrtarifa),0,0,'C');

        $pdf->Ln(2);

        foreach ($parametrosheader as $parametro) {
            $pdf->setY($pdf->getY() + 5);
            $pdf->setX($maxWidth*0.06);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$parametro->value_text,0,0,'C');
        }


        $pdf->setY($pdf->getY() + 12);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 36);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper($movimiento->placa),0,0,'C');
        
        $pdf->Ln(2);
        
        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY() + 12, $maxWidth*0.93, $pdf->getY() + 12);

        $pdf->Ln(4);

        $pdf->Code128($maxWidth*0.06,$pdf->getY() + 12,$movimiento->placa,$maxWidth*0.88,$cellHeightHeader);
        
        $pdf->setY($pdf->getY() + 30);
        $pdf->setX($maxWidth*0.15);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'FECHA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhentrada[0],0,0,'L');
        
        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.15);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'HORA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhentrada[1],0,0,'L');
        $pdf->Ln();
        foreach ($parametrosfooter as $parametro) {
            $pdf->SetLineWidth(1.5);
            $pdf->Line($maxWidth*0.06, $pdf->getY() + 5, $maxWidth*0.93, $pdf->getY() + 5);
            
            $pdf->setY($pdf->getY() + 8);
            $pdf->setX($maxWidth*0.06);
            $pdf->SetFont('Arial', 'B', $parametro->value_int);
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
        $txttarifas = 'Tarifas: ';
        $txttipovehiculo = 'Tipos de Vehiculos ';
        $fhentrada = explode(" ", $movimiento->fhentrada);
        $fhsalida = explode(" ", $movimiento->fhsalida);
        $pdf = new Fpdf('P','mm',array(82,200));
        $pdf->AddPage();
        $maxHeight = $pdf->h;
        $maxWidth = $pdf->w;
        $cellHeightHeader = 15;
        $cellHeight = 10;
        $pdf->SetLineWidth(1);
        $pdf->Line($maxWidth*0.06, $pdf->getY(), $maxWidth*0.93, $pdf->getY());
        
        $pdf->setY(7);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$empresa->nombre,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,"NIT. ".$empresa->nit,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$empresa->direccion,0,0,'C');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,Auth::user()->sede->nsede,0,0,'C');

        $pdf->Line($maxWidth*0.06, $pdf->getY() + 11, $maxWidth*0.93, $pdf->getY() + 11);

        foreach ($tarifas as $tarifa) {
            $txttarifas .= $tarifa->ntarifa.'-'.$tarifa->vrtarifa.' ';
        }
        foreach ($tipovehiculos as $tipovehiculo) {
            $txttipovehiculo .= $tipovehiculo->detalle.' ';
        }

        $pdf->Ln(4);
        
        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'PLACA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,mb_strtoupper($movimiento->placa),0,0,'C');
        
        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'FECHA INGRESO',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhentrada[0],0,0,'C');        
        
        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'FECHA SALIDA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhsalida[0],0,0,'C');

        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'HORA INGRESO',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhentrada[1],0,0,'C');


        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'HORA SALIDA',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$fhsalida[1],0,0,'C');
        
        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'TIEMPO',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,$movimiento->tiempo,0,0,'C');
        
        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.13);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($maxWidth*0.34, $cellHeightHeader,'VALOR A PAGAR',0,0,'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.54, $cellHeightHeader,number_format($movimiento->vrpagar,0),0,0,'C');

        $pdf->setY($pdf->getY() + 8);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,mb_strtoupper($movimiento->tipovehiculo->ntipov.' - Tarifa: '.$movimiento->tarifa->ntarifa.' $'.$movimiento->tarifa->vrtarifa),0,0,'C');

        $pdf->setY($pdf->getY() + 6);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$txttarifas,0,0,'C');

        $pdf->Ln();


        foreach ($parametrosfooter as $parametro) {
            $pdf->SetLineWidth(1.5);
            $pdf->Line($maxWidth*0.06, $pdf->getY() + 5, $maxWidth*0.93, $pdf->getY() + 5);
            
            $pdf->setY($pdf->getY() + 8);
            $pdf->setX($maxWidth*0.06);
            $pdf->SetFont('Arial', 'B', $parametro->value_int);
            $pdf->MultiCell($maxWidth*0.88,4,utf8_decode($parametro->value_text),0,'C',0);

        }

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