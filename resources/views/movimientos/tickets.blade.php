@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">
	    <table class="table-responsive" id="table-tickets">
	        <thead>
	            <tr>
	                <th>Placa</th>
	                <th>Fecha Entrada</th>
	                <th>Fecha Salida</th>
	                <th>Tarifa</th>
	                <th>Tipo Vehiculo</th>
	                <th>Tipo De Movimiento</th>
	                <th>Entrada</th>
	                <th>Salida</th>
	            </tr>
	        </thead>
	        <tbody>
	                <tr v-for="movimiento in movimientos" :id="movimiento.cmovi">
	                        <td>[[movimiento.placa]]</td>
	                        <td>[[movimiento.fhentrada]]</td>
	                        <td v-if="movimiento.fhsalida" >[[movimiento.fhsalida]]</td>
	                        <td v-else>SIN SALIDA AUN</td>
	                        <td>[[movimiento.tarifa.ntarifa]]</td>
	                        <td>[[movimiento.tipovehiculo.ntipov]]</td>
	                        <td>[[movimiento.timovi.ntimovi]]</td>
	                        <td>
	                            <button class="btn btn-primary" @click="printTicket('entrada',movimiento.cmovi)" >Imprimir</button>
	                        </td>
	                        <td v-if="movimiento.fhsalida">
	                            <button class="btn btn-primary" @click="printTicket('salida',movimiento.cmovi)"  >Imprimir</button>
	                        </td>
	                </tr>
	        </tbody>
	    </table>
    </div>
</template>
@endsection
