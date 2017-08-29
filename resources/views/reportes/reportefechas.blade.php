@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">
        <div class="container">
            
            <div class="row">
                
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <center><h1>Listado Por Fecha</h1></center>
                        </div>
                        <div class="panel-body">
                        <table class="table-responsive" id="table">
                            <thead>
                                <tr>
                                    <th>Placa</th>
                                    <th>Fecha Entrada</th>
                                    <th>Fecha Salida</th>
                                    <th>Tarifa</th>
                                    <th>Tipo Vehiculo</th>
                                    <th>Valor calculado</th>
                                    <th>Valor descontado</th>
                                    <th>Total pagado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="movimiento in movimientos">
                                    <td>[[movimiento.placa]]</td>
                                    <td>[[movimiento.fhentrada]]</td>
                                    <td>[[movimiento.tarifa.ntarifa]]</td>
                                    <td>[[movimiento.tipovehiculo.ntipov]]</td>
                                    <td>[[movimiento.timovi.ntimovi]]</td>
                                    <td>                             </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>    
</template>
@endsection