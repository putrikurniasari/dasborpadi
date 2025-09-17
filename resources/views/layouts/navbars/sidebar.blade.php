<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo" style="text-align:center;">
            <a href="#" class="simple-text logo-normal">{{ __('MONITORING PADI') }}</a>
        </div>
        <ul class="nav">
            <li @if (isset($pageSlug) && $pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li @if (isset($pageSlug) && $pageSlug == 'profile') class="active " @endif>
                <a href="{{ route('profile.edit') }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ __('User Profile') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>