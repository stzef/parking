<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use \App\Models\Movimientos;
use \App\Models\Tarifas;
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
                'sedes_id' => 'required|exists:sedes,id',
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
        $movimientos = Movimientos::find('placa',$dataBody['placa'])->first();
        if($movimientos){
            $validator = Validator::make($dataBody,
                [  
                    'placa' => 'required|exists:movimientos,placa|max:15',
                    'fhsalida' => 'required',
                ],
                [
                    'placa.required' => 'required',
                    'fhsalida.required' => 'required',
                ]
            );
            if ($validator->fails()){
                $messages = $validator->messages();
                return response()->json(array("errors_form" => $messages),400);
            }else{
                
                $movimientos->save($dataBody);
                var_dump("hola");exit();
            }
            return response()->json(array("obj" => $movimientos->toArray()));
        }
    }
    public function pdf(Request $request,$cmovi=1){
        $movimiento = Movimientos::where("cmovi",$cmovi)->first();
        $title = "TIQUETE DE ENTRADA";
        $pdf = new Fpdf('P','mm',array(85,85));
        $pdf->AddPage();
        $maxHeight = $pdf->h;
        $maxWidth = $pdf->w;
        $cellHeightHeader = 15;
        $cellHeight = 10;
        $pdf->SetFillColor(115,115,115);
        $pdf->setY(5);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($maxWidth*0.88, $cellHeightHeader,$title,0,0,'C');

        $pdf->setY($pdf->getY()+15);
        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell($maxWidth*0.44,$cellHeight,'PLACA',1,0,'C',1);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($maxWidth*0.44,$cellHeight,$movimiento->placa,1,0,'C');


        $pdf->Ln();

        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell($maxWidth*0.44,$cellHeight,'TARIFA',1,0,'C',1);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($maxWidth*0.44,$cellHeight,$movimiento->ctarifa,1,0,'C');

        $pdf->Ln();

        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell($maxWidth*0.44,$cellHeight,'TIPO DE VEHICULO',1,0,'C',1);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($maxWidth*0.44,$cellHeight,$movimiento->placa,1,0,'C');
        
        $pdf->Ln();

        $pdf->setX($maxWidth*0.06);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell($maxWidth*0.44,$cellHeight,'FECHA Y HORA DE ENTRADA',1,0,'C',1);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($maxWidth*0.44,$cellHeight,$movimiento->fhentrada,1,0,'C');

        
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