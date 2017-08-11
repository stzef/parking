@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">
        <div class="col-md-12">
            <div class="panel-heading">
                <center><h1>Entrada</h1></center>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                <form @submit.prevent="CreateEntrada" accept-charset="utf-8">
                    <input type="hidden" name="_token" id="token" value="{{csrf_token()}}"></input>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="label-control col-md-12 text-center">Fecha y Hora de entrada</label>
                            <div class="input-group">
                                <input type="text" name="fhentrada" v-model="entrada.fhentrada" class="form-control col-md-8" :value="entrada.fhentrada" disabled>
                                <span class="input-group-btn"><button type="button" class="btn btn-default"  @click="GenInTime()">Generar</button></span>                        
                            </div>
                        </div>
                        <div class="form-group col-md-4 col-md-offset-1">
                            <label for="placa" class="label-control col-md-12 text-center">Placa</label>
                            <div class="col-md-12">
                                <input type="text" name="placa" class="form-control" v-model="entrada.placa" @change="entrada.placa = slugify($event.target.value)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="label-control col-md-12 text-center">Tarifa</label>
                            <div class="col-md-12">
                                <select-tariff id="ctarifa" requeried="true" :tarifas="tarifas" :entrada="entrada"></select-tariff>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="label-control col-md-12 text-center">Tipo de vehiculo</label>
                            <div class="col-md-12">
                                <select-tyve id="ctipov" requeried="true" :tipovehiculo="tipovehiculo" :entrada="entrada"></select-tyve>
                            </div>
                        </div>
                            
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12">
                            <center><button type="submit" class="btn btn-success">Generar Entrada</button></center>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>    
</template>
@endsection