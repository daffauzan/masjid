<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-fw fa-mosque"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Masjid Abaabil</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Beranda</span>
        </a>
    </li>   

    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.konten.index') }}">
            <i class="fas fa-fw fa-file"></i>
            <span>Informasi &amp; Dakwah</span>
        </a>
    </li>
    
    <hr class="sidebar-divider">

    <li class="nav-item {{ request()->routeIs('admin.pos.zakat.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.pos.zakat.index') }}">
            <i class="fas fa-fw fa-cash-register"></i>
            <span>POS Zakat</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.rekening.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.rekening.index') }}">
            <i class="fas fa-fw fa-university"></i>
            <span>No Rekening</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>List User</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.assessment.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.assessment.index') }}">
            <i class="fas fa-fw fa-calculator"></i>
            <span>Assessment Zakat</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.detect.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.detect.index') }}">
            <i class="fas fa-fw fa-camera"></i>
            <span>Deteksi Uang</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <li class="nav-item {{ request()->routeIs('admin.zakat.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseZakat"
            aria-expanded="{{ request()->routeIs('admin.zakat.*') ? 'true' : 'false' }}" aria-controls="collapseZakat">
            <i class="fas fa-fw fa-money-bill"></i>
            <span>Data Zakat</span>
        </a>
        <div id="collapseZakat" class="collapse {{ request()->routeIs('admin.zakat.*') ? 'show' : '' }}"
            aria-labelledby="headingZakat" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Kelola Zakat</h6>
                <a class="collapse-item {{ request()->routeIs('admin.zakat.index') ? 'active' : '' }}"
                    href="{{ route('admin.zakat.index') }}">
                    <i class="fas fa-list fa-sm mr-1"></i> Semua Transaksi
                </a>
                <a class="collapse-item {{ request()->routeIs('admin.zakat.create') ? 'active' : '' }}"
                    href="{{ route('admin.zakat.create') }}">
                    <i class="fas fa-plus fa-sm mr-1"></i> Tambah Data
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>