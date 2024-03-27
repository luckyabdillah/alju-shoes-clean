<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" >
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">alju shoes clean</span> -->
            <h4 class="app-brand-text demo menu-text fw-bolder text-white mt-4">ALJU SHOES CLEAN</h4>
            {{-- <img src="{{ asset('assets/img/icons/brands/ALJU SHOES CLEAN LOGO - PNG.png') }}" alt="" style="width: 65%; z-index: 99; margin-bottom: 0.5em;" class="app-brand-logo"/> --}}
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none bg-danger" >
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaction</span>
        </li>

        <li class="menu-item {{ Request::is('dashboard/transaction/dropzone*') ? 'active' : '' }}">
            <a href="/dashboard/transaction/dropzone" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div>Dropzone</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('dashboard/transaction/pickup-delivery*') ? 'active' : '' }}">
            <a href="/dashboard/transaction/pickup-delivery" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cycling"></i>
                <div>Pickup & Delivery</div>
            </a>
        </li>

        <!-- Components -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>
        <!-- Cards -->
        <li class="menu-item {{ Request::is('dashboard/master-data/campaign*') ? 'active' : '' }}">
            <a href="/dashboard/master-data/campaign" class="menu-link">
                <i class="menu-icon tf-icons bx bx-crown"></i>
                <div>Campaign</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('dashboard/master-data/gallery*') ? 'active' : '' }}">
            <a href="/dashboard/master-data/gallery" class="menu-link">
                <i class="menu-icon tf-icons bx bx-photo-album"></i>
                <div>Gallery</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('dashboard/master-data/outlet*') ? 'active' : '' }}">
            <a href="/dashboard/master-data/outlet" class="menu-link">
                <i class="menu-icon tf-icons bx bx-buildings"></i>
                <div>Outlet</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('dashboard/master-data/user*') ? 'active' : '' }}">
            <a href="/dashboard/master-data/user" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>User</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('dashboard/master-data/customer*') ? 'active' : '' }}">
            <a href="/dashboard/master-data/customer" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div>Customer</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('dashboard/master-data/treatment*') ? 'active' : '' }}">
            <a href="/dashboard/master-data/treatment" class="menu-link">
                <i class="menu-icon tf-icons bx bx-purchase-tag-alt"></i>
                <div>Treatment</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Management</span>
        </li>

        <li class="menu-item {{ Request::is('dashboard/management/transaction-report*') ? 'active' : '' }}">
            <a href="/dashboard/management/transaction-report" class="menu-link">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div>Transaction Report</div>
            </a>
        </li>
    </ul>
</aside>