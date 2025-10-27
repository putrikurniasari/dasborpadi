<div class="sidebar custom-sidebar">
    <div class="sidebar-wrapper">
        <div class="logo" style="text-align:center;">
            <a href="#" class="simple-text logo-normal">{{ __('MONITORING PADI') }}</a>
        </div>
        <ul class="nav">

            <li @if (isset($pageSlug) && $pageSlug == 'dashboard') class="active" @endif>
                <a href="{{ route('home') }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>

            <li @if (isset($pageSlug) && in_array($pageSlug, ['realisasi-umkm', 'pembelian-padi'])) class="active"
            @endif>
                <a data-toggle="collapse" href="#excelMenu"
                    aria-expanded="{{ isset($pageSlug) && in_array($pageSlug, ['realisasi-umkm', 'pembelian-padi']) ? 'true' : 'false' }}">
                    <i class="fa-solid fa-file-excel"></i>
                    <p>
                        {{ __('File Excel') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ isset($pageSlug) && in_array($pageSlug, ['realisasi-umkm', 'pembelian-padi']) ? 'show' : '' }}"
                    id="excelMenu">
                    <ul class="nav">
                        <li @if (isset($pageSlug) && $pageSlug == 'realisasi-umkm') class="active" @endif>
                            <a href="{{ route('excel.realisasi_umkm') }}">
                                <span class="sidebar-mini-icon">RU</span>
                                <span class="sidebar-normal">{{ __('Realisasi Padi UMKM') }}</span>
                            </a>
                        </li>
                        <li @if (isset($pageSlug) && $pageSlug == 'pembelian-padi') class="active" @endif>
                            <a href="{{ route('excel.pembelian_padi') }}">
                                <span class="sidebar-mini-icon">PP</span>
                                <span class="sidebar-normal">{{ __('Pembelian Padi') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li @if (isset($pageSlug) && $pageSlug == 'profile') class="active" @endif>
                <a href="{{ route('profile.edit') }}">
                    <i class="fa-solid fa-user"></i>
                    <p>{{ __('User Profile') }}</p>
                </a>
            </li>

            <!-- End Menu Dropdown -->
        </ul>
    </div>
    <style>
        /* Reset list */
        .custom-sidebar .nav,
        .custom-sidebar .nav ul,
        .custom-sidebar .nav li {
            list-style: none !important;
        }

        /* Base link style */
        .custom-sidebar .nav li>a {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.35s ease;
            overflow: hidden;
            z-index: 1;
        }

        /* Icon animation */
        .custom-sidebar .nav li>a i {
            font-size: 1.1rem;
            transition: transform 0.35s ease, color 0.35s ease;
        }

        /* Highlight slide effect */
        .custom-sidebar .nav li>a::before {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.15);
            transition: left 0.35s ease;
            z-index: -1;
        }

        /* Hover effect */
        .custom-sidebar .nav li>a:hover::before {
            left: 0;
        }

        .custom-sidebar .nav li>a:hover {
            transform: translateX(6px);
        }

        .custom-sidebar .nav li>a:hover i {
            transform: translateX(4px) scale(1.15);
        }

        /* Text slide effect */
        .custom-sidebar .nav li>a:hover p,
        .custom-sidebar .nav li>a:hover span {
            transform: translateX(4px);
            transition: transform 0.35s ease;
        }

        /* Active state */
        .custom-sidebar .nav li.active>a {
            background: rgba(255, 255, 255, 0.25) !important;
            border-left: 3px solid #fff;
            transform: translateX(6px);
            color: #fff !important;
            font-weight: bold;
        }

        .custom-sidebar .nav li.active>a i {
            color: #fff !important;
        }

        /* Ensure active state keeps highlight */
        .custom-sidebar .nav li.active>a::before {
            left: 0;
        }

        /* Dropdown caret */
        .custom-sidebar .nav li>a[data-toggle="collapse"] p {
            flex: 1;
            margin: 0;
        }

        .custom-sidebar .nav li>a[data-toggle="collapse"] .caret {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .custom-sidebar .nav li>a[data-toggle="collapse"][aria-expanded="true"] .caret {
            transform: rotate(0deg);
        }

        .custom-sidebar .nav li>a[data-toggle="collapse"] .caret {
            transform: rotate(180deg);
        }
    </style>


</div>