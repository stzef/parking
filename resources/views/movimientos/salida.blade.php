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
                    <div class="form-group col-md-6">
                        <label for="" class="label-control col-md-12 text-center">Fecha y Hora de salida</label>
                        <div class="input-group">
                            <input type="text" name="fhsalida" v-model="salida.fhsalida" class="form-control col-md-8" :value="entrada.fhsalida" disabled required>
                            <span class="input-group-btn"><button type="button" class="btn btn-default"  @click="GenOutTime()">Generar</button></span>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="placa" class="label-control col-md-12 text-center">Placa</label>
                        <div class="col-md-12">
                            <select-placa :item="salida" :placas="placas"></select-placa>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12">
                            <center><button type="submit" class="btn btn-success">Buscar</button></center>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>    
</template>
@endsection