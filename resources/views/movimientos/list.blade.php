@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">

            <div class="row">
                <form action="" method="get" accept-charset="utf-8">
                    <div class="form-group col-md-6">
                        <center><label class="label-control col-md-12">Fecha Entrada</label></center>
                        <input type="text" name="" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <center><label class="label-control col-md-12">Fecha Salida</label></center>
                        <div class="row">
                          <span>Departure Date：</span>
                          <input type="datetime" is="datetime" />
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <center><button class="btn btn-success">Buscar</button></center>
                    </div>
                </form>
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
</template>
@endsection