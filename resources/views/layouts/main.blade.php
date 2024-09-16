<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-QXWRLCC4QK"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-QXWRLCC4QK', {
      cookie_flags: 'SameSite=None;Secure'
    });
  </script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TraningKerja.com</title>
  <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Montserrat'>
  <link rel="stylesheet" href="{{ asset('/') }}plugins/fontawesome-free/css/all.min.css">
  @yield('headers')
  <link rel="stylesheet" href="{{ asset('/') }}dist/css/adminlte.min.css">
  <style>
    html {
      font-family: "Montserrat" !important;
    }
  </style>
</head>

<body>
  @yield('content')
</body>