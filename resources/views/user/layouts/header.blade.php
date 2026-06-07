<header id="header" class="header d-flex align-items-center sticky-top">
  @php
    $isUserLoggedIn = auth()->check() && auth()->user()->role === 'user';
  @endphp
  <div class="container-fluid container-xl position-relative d-flex align-items-center">

    <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
      <h1 class="sitename">Masjid Abaabil</h1>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="{{ url('/') }}" class="{{ request()->path() === '/' ? 'active' : '' }}">Beranda</a></li>
        <li class="dropdown">
          <a href="#"><span>Informasi & Konten</span> <i class="bi bi-chevron-down"></i></a>
          <ul>
            <li><a href="#Informasi">Informasi</a></li>
            <li><a href="#Konten">Konten</a></li>
          </ul>
        </li>

        @if($isUserLoggedIn)
          <li class="dropdown">
            <a href="#"><span>Zakat</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="{{ route('user.assessment.create') }}">Isi Assessment</a></li>
              <li><a href="{{ route('user.assessment.index') }}">Riwayat Assessment</a></li>
            </ul>
          </li>
        @endif
        <li class="dropdown">
            <a href="#"><span>Lainnya</span> <i class="bi bi-chevron-down"></i></a>
          <ul>
            <li><a href="{{ route('pages.jadwal-shalat') }}" class="{{ request()->routeIs('pages.jadwal-shalat') ? 'active' : '' }}">Jadwal Shalat</a></li>
            <li><a href="{{ route('pages.about') }}" class="{{ request()->routeIs('pages.about') ? 'active' : '' }}">Tentang</a></li>
          </ul>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    @if($isUserLoggedIn)
      <div class="dropdown">
        <button class="btn-getstarted border-0 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle me-1"></i> Profile
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li>
            <a class="dropdown-item" href="{{ route('user.assessment.index') }}">
              <i class="bi bi-clipboard-data me-2"></i>Riwayat Assessment
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('user.assessment.create') }}">
              <i class="bi bi-plus-circle me-2"></i>Buat Assessment
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('user.logout') }}" method="POST" class="m-0">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    @else
      <a class="btn-getstarted" href="{{ route('admin.login') }}">Login</a>
    @endif
  </div>
</header>
