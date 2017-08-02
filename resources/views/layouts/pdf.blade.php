<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title> Title </title>

  <style>
  </style>
  @yield('styles')
</head>
<body id="app-layout">

    <header id="header">
      @yield('header')
    </header>
    
    @yield('body')

    <footer id="footer">
        @yield('footer')
    </footer>


    @yield('scripts')
</body>
</html>