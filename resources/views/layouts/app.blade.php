<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Parking') }}</title>

    <!-- Styles -->
    <link href="{{ asset('node_modules/datatables.net-dt/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('node_modules/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <style type="text/css" media="screen">
        a.btn {
            text-decoration: none;
            color: initial;
        }
        a{
          font-size: 18px;
        }
        .placa{
            text-transform: uppercase;
        }
        .switch {
          position: relative;
          display: inline-block;
          width: 90px;
          height: 34px;
        }
        .clock {
          background-color: #263238;
          color: #eceff1;
          padding: .3rem .6rem;
          font-size: 5rem;
          font-family: 'Menlo', monospace;
        }
        .switch input {display:none;}

        .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #a9adb2;
          -webkit-transition: .4s;
          transition: .4s;
        }

        .slider:before {
          position: absolute;
          content: "";
          height: 26px;
          width: 26px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
        }

        input:checked + .slider {
          background-color: #4592f7;
        }

        input:focus + .slider {
          box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
          -webkit-transform: translateX(55px);
          -ms-transform: translateX(55px);
          transform: translateX(55px);
        }

        /*------ ADDED CSS ---------*/
        .on
        {
          display: none;
          color: white;
          position: absolute;
          transform: translate(-50%,-50%);
          top: 50%;
          left: 40%;
          font-size: 15px;
          font-family: Verdana, sans-serif;
        }
        
        .off
        {
          color: white;
          position: absolute;
          transform: translate(-50%,-50%);
          top: 50%;
          left: 60%;
          font-size: 15px;
          font-family: Verdana, sans-serif;
        }

        input:checked+ .slider .on
        {display: block;}

        input:checked + .slider .off
        {display: none;}

        /*--------- END --------*/

        /* Rounded sliders */
        .slider.round {
          border-radius: 34px;
        }

        .slider.round:before {
          border-radius: 50%;}
        input, select {
            padding: .75em .5em;
            font-size: 100%;
            border: 1px solid #ccc;
            width: 100%
        }
    </style>
</head>
<body>
    <div id="app">
        <nav id="top-nav" class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Parking') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                    </ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Iniciar Sesion</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Cerrar Sesion
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>

                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @if(Auth::guest())
            @yield('content')
        @else    
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <center><Clock :blink="true" /></center>
                        <hr>
                        <ul class="nav nav-stacked">
                            <li><a href="{{URL::route('entrada')}}" class="btn btn-block"><i class="glyphicon glyphicon-upload"></i>   ENTRADA</a></li>
                            <li><a href="{{URL::route('salida')}}" class="btn btn-block"><i class="glyphicon glyphicon-download"></i> SALIDA</a></li>
                            @role('admin') 
                                <li><a href="{{URL::route('lista')}}" class="btn btn-block" @click="list()"><i class="glyphicon glyphicon-list-alt"></i> SALIDAS POR FECHAS </a></li>
                                <li><a href="{{URL::route('config')}}" class="btn btn-block" @click="list()"><i class="glyphicon glyphicon-list-alt"></i> CONFIGURACION </a></li>
                            @endrole
                        </ul>
                    <hr>
                    </div>
                    @yield('content')
                </div>
            </div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('node_modules/alertifyjs/build/alertify.min.js') }}"></script>
    <script src="{{ asset('/node_modules/datatables.net/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript">
      var symbol_currency = "$"
      function CurrencyFormat(){
        //numberFormat = Intl.NumberFormat({style:"currency",currency:"COP",currencyDisplay:"symbol"})
        this.numberFormat = Intl.NumberFormat("es-419")
      }
      CurrencyFormat.prototype.format = function(number){
        if(this.numberFormat.format(number) == "NaN") return symbol_currency+" 0"
        return symbol_currency+" " + this.numberFormat.format(number)
      }
      CurrencyFormat.prototype.clear = function(number){
        return number.replace(",","").replace(/[^\d\.\,\s]+/g,"").trim()
      }
      CurrencyFormat.prototype.sToN = function(s){
        var n = parseFloat(s.replace(/ /g,"").replace(/,/g,"").replace(/[^\d\.\,\s]+/g,"").trim())//.replace(/\./g,"")
        return n
      }
      var currencyFormat = new CurrencyFormat()
    </script>
    <script type="text/javascript" src="{{asset('dist/build.js')}}"></script>
</body>
</html>
