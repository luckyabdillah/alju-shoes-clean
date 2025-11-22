<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" >
    <div class="app-brand demo">
        <a href="/dashboard" class="app-brand-link">
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">alju shoes clean</span> -->
            <h4 class="app-brand-text demo menu-text fw-bolder mt-4">ALJU SHOES CLEAN</h4>
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

        {{-- <li class="menu-item {{ Request::is('dashboard/master-data*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-hive"></i>
                <div>Master Data</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('dashboard/master-data/campaign') ? 'active' : '' }}">
                    <a href="/dashboard/master-data/campaign" class="menu-link">
                        Campaign
                    </a>
                </li>
                <li class="menu-item {{ Request::is('dashboard/master-data/gallery') ? 'active' : '' }}">
                    <a href="/dashboard/master-data/gallery" class="menu-link">
                        Gallery
                    </a>
                </li>
                <li class="menu-item {{ Request::is('dashboard/master-data/outlet') ? 'active' : '' }}">
                    <a href="/dashboard/master-data/outlet" class="menu-link">
                        Outlet
                    </a>
                </li>
                <li class="menu-item {{ Request::is('dashboard/master-data/customer') ? 'active' : '' }}">
                    <a href="/dashboard/master-data/customer" class="menu-link">
                        Customer
                    </a>
                </li>
                <li class="menu-item {{ Request::is('dashboard/master-data/treatment') ? 'active' : '' }}">
                    <a href="/dashboard/master-data/treatment" class="menu-link">
                        Treatment
                    </a>
                </li>
                <li class="menu-item {{ Request::is('dashboard/master-data/config') ? 'active' : '' }}">
                    <a href="/dashboard/master-data/config" class="menu-link">
                        Config
                    </a>
                </li>
            </ul>
        </li> --}}

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>

        @canany(['operation', 'administrator'])
            <li class="menu-item {{ Request::is('dashboard/transaction/dropzone*') ? 'active' : '' }}">
                <a href="/dashboard/transaction/dropzone" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div>Dropzone</div>
                </a>
            </li>
        @endcanany

        @canany(['driver', 'administrator'])
            <li class="menu-item {{ Request::is('dashboard/transaction/pickup-delivery*') ? 'active' : '' }}">
                <a href="/dashboard/transaction/pickup-delivery" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cycling"></i>
                    <div>Pickup & Delivery</div>
                </a>
            </li>
        @endcanany

        @can('administrator')
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

            <li class="menu-item {{ Request::is('dashboard/master-data/promo-code*') ? 'active' : '' }}">
                <a href="/dashboard/master-data/promo-code" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-discount"></i>
                    <div>Kode Promo</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Management</span>
            </li>

            <li class="menu-item {{ Request::is('dashboard/user*') ? 'active' : '' }}">
                <a href="/dashboard/user" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>User</div>
                </a>
            </li>

            <li class="menu-item {{ Request::is('dashboard/report*') ? 'active' : '' }}">
                <a href="/dashboard/report" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-detail"></i>
                    <div>Laporan Transaksi</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Administrator</span>
            </li>

            <li class="menu-item {{ Request::is('dashboard/config*') ? 'active' : '' }}">
                <a href="/dashboard/config" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div>Config</div>
                </a>
            </li>

            <li class="menu-item {{ Request::is('dashboard/transaction') || Request::is('dashboard/transaction/*/edit') ? 'active' : '' }}">
                <a href="/dashboard/transaction" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-edit-alt"></i>
                    <div>Edit Transaksi</div>
                </a>
            </li>
        @endcan
    </ul>
</aside>