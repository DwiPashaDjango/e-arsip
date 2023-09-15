<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title', 'Home') &mdash; {{ config('app.name') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link rel="stylesheet" href="{{asset('js/vendor/datatable/datatable.min.css')}}">
  <link rel="stylesheet" href="{{asset('js/vendor/datatable/datatablebs4.min.css')}}">
  <link rel="stylesheet" href="{{asset('js/vendor/datatable/responsive.bootstrap.min.css')}}">
  @stack('css')
</head>

<body>
<div id="app">
  @yield('app')
</div>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{asset('js/vendor/datatable/datatable.min.js')}}"></script>
<script src="{{asset('js/vendor/datatable/datatablebs4.min.js')}}"></script>
<script src="{{asset('js/vendor/datatable/datatableResponsive.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('modal')
@stack('js')
</body>
</html>
