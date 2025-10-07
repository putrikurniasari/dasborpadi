<div class="sidebar animate__animated animate__slideInLeft">
    <div class="sidebar-wrapper">
        <div class="logo" style="text-align:center;">
            <a href="#" class="simple-text logo-normal">{{ __('MONITORING PADI') }}</a>
        </div>
        <ul class="nav">

            <!-- dashboard menu -->
            <li @if (isset($pageSlug) && $pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>

            <!-- Menu Dropdown File Excel -->
            <li @if (isset($pageSlug) && in_array($pageSlug, ['realisasi-umkm', 'pembelian-padi'])) class="active"
            @endif>
                <a data-toggle="collapse" href="#excelMenu"
                    aria-expanded="{{ isset($pageSlug) && in_array($pageSlug, ['realisasi-umkm', 'pembelian-padi']) ? 'true' : 'false' }}">
                    <i class="tim-icons icon-paper"></i>
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

            <!-- user profil menu -->
             <li @if (isset($pageSlug) && $pageSlug == 'profile') class="active " @endif>
                <a href="{{ route('profile.edit') }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ __('User Profile') }}</p>
                </a>
            </li>
            <!-- End Menu Dropdown -->
        </ul>
    </div>
</div>