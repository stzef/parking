@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">
        <div class="col-md-12">
            <div class="panel-heading">
                <center><h1>Lista</h1></center>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                <table class="table-responsive" id="table">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Fecha Entrada</th>
                            <th>Tarifa</th>
                            <th>Tipo Vehiculo</th>
                            <th>¿Como le pongo?</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="movimiento in movimientos">
                            <td>[[movimiento.placa]]</td>
                            <td>[[movimiento.fhentrada]]</td>
                            <td>[[movimiento.tarifa.ntarifa]]</td>
                            <td>[[movimiento.tipovehiculo.ntipov]]</td>
                            <td>[[movimiento.timovi.ntimovi]]</td>
                            <td>
                                <button class="btn btn-primary" @click="setPlaca(movimiento.placa,movimiento.cmovi)" :disabled="movimiento.ctimovi == 2" data-dismiss="modal">Seleccionar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>    
</template>
@endsection