@extends('layouts.app')

@section('content')
<div class="col-sm-9">
	<vueble :items="movimientos" lang="es" :searcheable-props="['placa']">
			<template slot="colums">
				<tr slot="colums">
					<th>Placa</th>
					<th>Fecha Entrada</th>
					<th>Fecha Salida</th>
					<th>Tarifa</th>
					<th>Tipo Vehiculo</th>
					<th>Tipo De Movimiento</th>
					<th>Entrada</th>
					<th>Salida</th>
				</tr>
			</template>
    		<template slot="row" scope="item">
	            <tr>
	                    <td>[[item.scope.placa]]</td>
	                    <td>[[item.scope.fhentrada]]</td>
	                    <td v-if="item.scope.fhsalida" >[[item.scope.fhsalida]]</td>
	                    <td v-else>SIN SALIDA AUN</td>
	                    <td>[[item.scope.tarifa.ntarifa]]</td>
	                    <td>[[item.scope.tipovehiculo.ntipov]]</td>
	                    <td>[[item.scope.timovi.ntimovi]]</td>
	                    <td>
	                        <button class="btn btn-primary" @click="printTicket('entrada',item.scope.cmovi)" >Imprimir</button>
	                    </td>
	                    <td v-if="item.scope.fhsalida">
	                        <button class="btn btn-primary" @click="printTicket('salida',item.scope.cmovi)"  >Imprimir</button>
	                    </td>
	            </tr>
    		</template>
	</vueble>
</div>
@endsection
