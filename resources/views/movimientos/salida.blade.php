@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">
        <div class="col-md-12">
            <div class="panel-heading">
                <center><h1>Salida</h1></center>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                <form @submit.prevent="CreateSalida" accept-charset="utf-8">
                    <input type="hidden" name="_token" id="token" value="{{csrf_token()}}"></input>
                    <div class="form-group col-md-4 col-md-offset-1">
                        <label for="placa" class="label-control col-md-12 text-center">Placa</label>
                        <div class="input-group">
                            <input type="text" name="fhsalida" v-model="salida.placa" class="form-control col-md-8" :value="salida.placa" required>
                            <span class="input-group-btn"><button type="button" class="btn btn-default" @click="list()" data-toggle="modal" data-target="#list">Buscar</button></span>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="" class="label-control col-md-12 text-center">Fecha y Hora de salida</label>
                        <div class="input-group">
                            <input type="text" name="fhsalida" v-model="salida.fhsalida" class="form-control col-md-8" :value="entrada.fhsalida" disabled required>
                            <span class="input-group-btn"><button type="button" class="btn btn-default"  @click="GenOutTime()">Generar</button></span>
                        </div>
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

          <!-- Modal Footer-->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>    
</template>
@endsection