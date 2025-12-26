<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar pc-trigger">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="../dashboard/index.html" class="b-brand text-primary d-flex align-items-center">
        <!-- Ganti logo dengan teks -->
        <span class="fw-bold fs-4">Monitoring Padi</span>
        <!-- <span class="badge bg-light-success rounded-pill ms-2 theme-version">v2.6.0</span> -->
      </a>
    </div>

    <div class="navbar-content">
      <div class="card pc-user-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <img src="{{ asset('assets/images/favicon.png') }}" alt="user-image" class="user-avtar wid-45 rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3 me-2">
              <h6 class="mb-0">{{ old('username', $user->username ?? '') }}</h6>
            </div>
            <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
              <svg class="pc-icon">
                <use xlink:href="#custom-sort-outline"></use>
              </svg>
            </a>
          </div>
          <div class="collapse pc-user-links" id="pc_sidebar_userlink">
            <div class="pt-3">
              <a href="{{ route('profile.edit') }}">
                <i class="ti ti-user"></i>
                <span>My Account</span>
              </a>
              <a href="{{ route('logout') }}">
                <i class="ti ti-power"></i>
                <span>Logout</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <ul class="pc-navbar">
        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>

        <li class="pc-item">
          <a href="{{ route('dashboard') }}" class="pc-link">
            <span class="pc-micon">
              <svg class="pc-icon">
                <use xlink:href="#custom-status-up"></use>
              </svg>
            </span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon">
              <svg class="pc-icon">
                <use xlink:href="#custom-level"></use>
              </svg> </span><span class="pc-mtext">Excel</span><span class="pc-arrow"><i
                data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('excel.pembelian_padi') }}">Pembelian Padi</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('excel.realisasi_umkm') }}">Realisasi Padi UMKM</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>