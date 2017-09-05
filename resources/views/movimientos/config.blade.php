@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <center><h2>CONFIGURACIÓN</h2></center>
            </div>
            <div class="panel-body">
                <input type="hidden" name="_token" id="token" value="{{csrf_token()}}">
                <form @submit.prevent="CreateSalida" accept-charset="utf-8">
                    <template v-for="param in params">
                        <div class="form-group col-md-6">
                            <label class="label-form col-md-12">[[param.name]]</label>
                            <textarea type="text" class="form-control col-md-6" :value="[[param.value_text]]"></textarea>
                        </div>
                    </template>
                    <div class="form-group col-md-12">
                        <center>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>    
</template>
@endsection