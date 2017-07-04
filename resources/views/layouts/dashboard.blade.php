@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <center><Clock :blink="true" /></center>
            <hr>
            <ul class="nav nav-stacked">
                <li><button class="btn btn-block"><i class="glyphicon glyphicon-upload"></i>   ENTRADA</button></li>
                <li><button class="btn btn-block"><i class="glyphicon glyphicon-download"></i> SALIDA</button></li>
                <li><button class="btn btn-block"><i class="glyphicon glyphicon-list-alt"></i> LISTA</button></li>
            </ul>
        <hr>
        </div>
        @yield('home')
</div>
@endsection