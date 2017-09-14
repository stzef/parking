@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <center><h2>SALIDAS POR FECHAS</h2></center>
            </div>
            <div class="panel-body">
                <form @submit.prevent="dataReport" accept-charset="utf-8">
                    <input type="hidden" name="_token" id="token" value="{{csrf_token()}}"></input>
                    <div class="form-group col-md-6">
                        <center><label class="label-control col-md-12">Desde</label></center>
                        <datepicker placeholder="Seleccione la fecha" format="yyyy-MM-dd" language="es" v-model="reportDate.Date1"></datepicker>
                    </div>
                    <div class="form-group col-md-6">
                        <center><label class="label-control col-md-12">Hasta</label></center>
                        <datepicker placeholder="Seleccione la fecha" format="yyyy-MM-dd" language="es" v-model="reportDate.Date2"></datepicker>
                    </div>
                    <div class="form-group col-md-4">
                        <center>
                            <label class="custom-radio" for="optradio">
                              <input type="radio" name="optradio">Ordenar por Tiempo
                            </label>
                        </center>
                    </div>
                    <div class="form-group col-md-4">
                        <center>
                            <label class="custom-radio">
                              <input type="radio" name="optradio">Ordenar por Valor Calculado
                            </label>
                        </center>    
                    </div>
                    <div class="form-group col-md-4">
                        <center>
                            <label class="custom-radio">
                              <input type="radio" name="optradio">Ordenar por Valor de Descuento
                            </label>
                        </center>    
                    </div>
                    <div class="form-group col-md-12">
                        <center><button type="submit" class="btn btn-success" >Buscar</button></center>
                    </div>
                </form>
            </div>
        </div>
    </div>    
</template>
@endsection