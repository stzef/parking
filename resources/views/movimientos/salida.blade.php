@extends('layouts.app')

@section('content')
<template>
    <div class="col-md-9">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <center><h2>SALIDA</h2></center>
                </div>
                <div class="panel-body">
                <form @submit.prevent="CreateSalida" accept-charset="utf-8">
                    <input type="hidden" name="_token" id="token" value="{{csrf_token()}}"></input>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="label-control col-md-12 text-center">Fecha y Hora de salida</label>
                            <div class="input-group">
                                <input type="text" name="fhsalida" v-model="salida.fhsalida" class="form-control col-md-8" disabled required>
                                <span class="input-group-btn"><button type="button" class="btn btn-default"  @click="GenOutTime()">Generar</button></span>                            
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="placa" class="label-control col-md-12 text-center">Placa</label>
                            <div class="input-group">
                                <input type="text" v-model="salida.placa" class="form-control col-md-8 placa" :value="salida.placa" @change="getMovimiento(slugify(salida.placa))" required>
                                <span class="input-group-btn"><button type="button" class="btn btn-default" @click="list()" data-toggle="modal" data-target="#list">Buscar</button></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="label-control col-md-12 text-center">Fecha y Hora de Entrada</label>
                            <input type="text" name="fhentrada" v-model="salida.fhentrada" class="form-control col-md-12" disabled required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-control col-md-12 text-center">Tiempo</label>
                            <input type="text" name="tiempo" v-model="salida.tiempo" class="form-control col-md-12" disabled="" :value = "salida.tiempo" @click="setTime()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="label-control col-md-12 text-center">Tarifa</label>
                            <div class="col-md-12">
                                <select-tariff id="ctarifa" requeried="true" :tarifas="tarifas" :obj="salida" disabled></select-tariff>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="label-control col-md-12 text-center">Tipo de vehiculo</label>
                            <div class="col-md-12">
                                <select-tyve id="ctipov" requeried="true" :tipovehiculo="tipovehiculo" :obj="salida" disabled></select-tyve>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="" class="label-control col-md-12 text-center">Valor Calculado</label>
                            <input type="text" name="vrdescuento" class="form-control" v-model="salida.vrpagar" @keyup="setValtotal()">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="" class="label-control col-md-12 text-center">Valor Descuento</label>
                            <input type="text" name="vrdescuento" class="form-control" v-model="salida.vrdescuento" @keyup="setValtotal()">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <label for="check" class="label-control col-md-12">Cortesia</label>
                                    <div class="col-md-12">
                                        <label class="switch">
                                            <input type="checkbox" id="check" v-model="salida.cortesia" @change="setDescu()">
                                            <span class="slider">
                                            <span class="on">SI</span><span class="off">NO</span>
                                            </span>
                                        </label>                   
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="" class="label-control col-md-12 text-center">Valor Total</label>
                            <input type="text" name="vrdescuento" class="form-control" v-model="salida.vrtotal" disabled>
                        </div>
                    </div>
                    <div class="row">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12">
                            <center><button type="submit" class="btn btn-success">Generar</button></center>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div id="list" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            
          <!-- Modal Header-->
          <div class="modal-header">
            <div class="col-md-10">
                <h4 class="modal-title">Historial</h4>
            </div>
            <div class="col-md-2">
                <center>
                    <button type="button" class="btn btn-block btn-danger" data-dismiss="modal">&times;</button>
                </center>
            </div>
          </div>
          
          <!-- Modal Body-->
          <div class="modal-body">
                <table class="table-responsive" id="table">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Fecha Entrada</th>
                            <th>Tarifa</th>
                            <th>Tipo Vehiculo</th>
                            <th>Tipo De Movimiento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="movimiento in movimientos">
                            <tr v-if="movimiento.ctimovi != 2" :id="movimiento.cmovi">
                                    <td>[[movimiento.placa]]</td>
                                    <td>[[movimiento.fhentrada]]</td>
                                    <td>[[movimiento.tarifa.ntarifa]]</td>
                                    <td>[[movimiento.tipovehiculo.ntipov]]</td>
                                    <td>[[movimiento.timovi.ntimovi]]</td>
                                    <td>
                                        <button class="btn btn-primary" @click="setData(movimiento)" :disabled="movimiento.ctimovi == 2" data-dismiss="modal">Seleccionar</button>
                                    </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
          </div>

          <!-- Modal Footer-->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>

      </div>
    </div>    
</template>
@endsection