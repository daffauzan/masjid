<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>@yield('title', 'Multi')</title>

  <!-- Favicons -->
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&family=Raleway&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'"> --> 
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'">

  <!-- Main CSS -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
  <style>
    :root {
      --accent-color: #1f8f3d;
      --heading-color: #0f4f24;
      --default-color: #264132;
      --surface-color: #f5fbf7;
    }

    .header .btn-getstarted,
    .hero .btn-get-started,
    .read-more,
    .scroll-top {
      background-color: var(--accent-color) !important;
      border-color: var(--accent-color) !important;
    }

    .section-title h2,
    .section-title .description-title {
      color: var(--heading-color);
    }
  </style>
</head>

<body class="index-page">

  @include('user.layouts.header')

  <main class="main">
    @yield('content')
  </main>

  @include('user.layouts.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Preloader -->
  <div id="preloader">
    <div></div><div></div><div></div><div></div>
  </div>

  <!-- Vendor JS -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script defer src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script defer src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>

  <!-- Main JS -->
  <script defer src="{{ asset('assets/js/main.js') }}"></script>

  <script>
    // Hide preloader setelah render, jangan tunggu load
    if (document.readyState !== 'loading') {
      setTimeout(() => {
        const preloader = document.querySelector('#preloader');
        if (preloader) preloader.style.display = 'none';
      }, 100);
    } else {
      document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
          const preloader = document.querySelector('#preloader');
          if (preloader) preloader.style.display = 'none';
        }, 100);
      });
    }
  </script>
</body>
</html>
