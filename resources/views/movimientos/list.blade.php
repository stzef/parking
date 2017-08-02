@extends('layouts.app')

@section('content')
<template>
    <div class="col-sm-9">
        <div class="col-md-12">
            <div class="panel-heading">
                <center><h1>Lista</h1></center>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                   <table class="table-responsive" id="table">
                        <thead>
                            <tr>
                                <th>header</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>data</td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>    
</template>
@endsection