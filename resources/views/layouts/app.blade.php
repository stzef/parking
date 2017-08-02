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
                            <li><a href="{{ route('register') }}">Registrarse</a></li>
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
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <center><Clock :blink="true" /></center>
                        <hr>
                        <ul class="nav nav-stacked">
                            <li><button href="{{URL::route('entrada')}}" class="btn btn-block"><i class="glyphicon glyphicon-upload"></i>   ENTRADA</button></li>
                            <li><button href="{{URL::route('salida')}}" class="btn btn-block"><i class="glyphicon glyphicon-download"></i> SALIDA</button></li>
                            <li><button href="#" class="btn btn-block"><i class="glyphicon glyphicon-list-alt"></i> LISTA</button></li>
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
    <script type="text/javascript" src="{{asset('dist/build.js')}}"></script>
    <script type="text/javascript">
        
    </script>
</body>
</html>
