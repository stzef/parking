@extends('layouts.dashboard')

@section('home')
    <div class="col-sm-9">
        <div class="col-md-12">
            <div class="panel-heading">
                <center><h1>Entrada</h1></center>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                <form action="" method="get" accept-charset="utf-8">
                    <div class="form-group col-md-6">
                        <label for="" class="label-control col-md-12 text-center">Fecha de entrada</label>
                        <div class="col-md-12">
                            <input type="text" name="fhentrada" v-model="time" class="form-control" :value="time" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-4 col-md-offset-1">
                        <label for="" class="label-control col-md-12 text-center">Placa</label>
                        <div class="col-md-12">
                            <input type="" name="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="" class="label-control col-md-12 text-center">Tipo de vehiculo</label>
                        <div class="col-md-12">
                            <select name="" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="" class="label-control col-md-12 text-center">Tarifa</label>
                        <div class="col-md-12">
                            <select name="" class="form-control">
                                <option value=""></option>
                            </select>
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
@endsection
