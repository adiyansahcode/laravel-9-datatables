<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="{{ config('app.name', 'Laravel 9') }} | @yield('title')">
    <title>{{ config('app.name', 'Laravel 9') }} | @yield('title') </title>

    <!-- favicon -->
    <!-- https://realfavicongenerator.net/ -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}" />
    <link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}" color="#5bbad5" />
    <meta name="msapplication-TileColor" content="#007f00" />
    <meta name="theme-color" content="#ffffff" />

    @stack('before-style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
      body {
        background: #f4f6f9;
        font-family: "Nunito",sans-serif;
      }

      .fill-blue {
        filter: invert(34%) sepia(52%) saturate(6351%) hue-rotate(211deg) brightness(101%) contrast(102%);
      }
    </style>
    @stack('after-style')
  </head>
  <body>
    <main>
      @yield('content')

      <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
          <div class="col-md-4 d-flex align-items-center">
            <a href="https://github.com/adiyansahcode" target="_blank" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
              adiyansahcode
            </a>
          </div>

          <div class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <span class="text-muted">&copy; 2022</span>
          </div>
        </footer>
      </div>

      @stack('before-scripts')
      <script src="{{ mix('js/app.js') }}"></script>
      <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        });
      </script>
      @stack('after-scripts')
    </main>
  </body>
</html>
